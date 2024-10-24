<?php

namespace Borah\KnowledgeBase\Traits;

use Borah\KnowledgeBase\Client\KnowledgeBaseClient;
use Borah\KnowledgeBase\DTO\KnowledgeBaseQueryResponse;
use Borah\KnowledgeBase\DTO\KnowledgeEmbeddingText;
use Borah\KnowledgeBase\DTO\KnowledgeInsertItem;
use Borah\KnowledgeBase\Facades\KnowledgeBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use function Illuminate\Events\queueable;

trait BelongsToKnowledgeBase
{
    protected string $knowledgeBaseKeyAttribute = null; 

    public static function bootBelongsToKnowledgeBase()
    {
        static::created(queueable(function (Model $model) {
            KnowledgeBase::upsertOne($model);
        }));

        static::updated(queueable(function (Model $model) {
            KnowledgeBase::upsertOne($model);
        }));

        static::deleted(queueable(function (Model $model) {
            KnowledgeBase::destroy($model);
        }));
    }

    public function knowledgeBaseChunks(): MorphMany
    {
        return $this->morphMany(config('knowledge_base.models.knowledge_base_chunk'), 'model');
    }

    public function knowledgeBasePayload(): array
    {
        return [];
    }

    /**
     * @return KnowledgeInsertItem[]
     */
    public function knowledgeInsertItems(): array
    {
        $texts = $this->getEmbeddingsTexts();
        if (!is_array($texts)) {
            $texts = [$texts];
        }

        $items = [];
        $existingChunks = $this->knowledgeBaseChunks()->get()->sortBy('order');
        foreach ($texts as $i => $text) {
            $chunk = $existingChunks[$i] ?? $this->knowledgeBaseChunks()->create([
                'text' => $text->text,
                'order' => $i,
            ]);

            if (!$chunk->wasRecentlyCreated && $chunk->text !== $text->text) {
                $chunk->update(['text' => $text->text]);
            }

            $key = $this->knowledgeBaseKeyAttribute ? $this->{$this->knowledgeBaseKeyAttribute} : $this->getKey();

            $items[] = new KnowledgeInsertItem(
                id: $chunk->id,
                entity: $text->entity,
                text: $text->text,
                payload: [
                    ...$this->knowledgeBasePayload(),
                    'original_record_id' => $this->$key,
                ],
            );
        }

        $chunksToDelete = $this->knowledgeBaseChunks()
            ->where('order', '>=', count($texts))
            ->select('id', 'order')
            ->get();
        $client = new KnowledgeBaseClient();
        foreach ($chunksToDelete as $chunk) {
            $client->destroy($chunk->id);
            $chunk->delete();
        }

        return $items;
    }

    public static function searchInKnowledgeBase(string $query, int $k = 10, ?array $where = null): KnowledgeBaseQueryResponse
    {
        return KnowledgeBase::query($query, $k, [class_basename(static::class)], $where);
    }
}
