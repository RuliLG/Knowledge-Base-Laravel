<?php

namespace Borah\KnowledgeBase\Traits;

use Borah\KnowledgeBase\DTO\KnowledgeEmbeddingText;
use Borah\KnowledgeBase\DTO\KnowledgeInsertItem;
use Borah\KnowledgeBase\Facades\KnowledgeBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use function Illuminate\Events\queueable;

trait BelongsToKnowledgeBase
{
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

    public function knowledgeBaseId(): MorphOne
    {
        return $this->morphOne(config('knowledge_base.models.knowledge_base_id'), 'model');
    }

    /**
     * @return KnowledgeInsertItem[]
     */
    public function knowledgeInsertItems(): array
    {
        $knowledgeBaseId = $this->knowledgeBaseId ?? $this->knowledgeBaseId()->create();
        $texts = $this->getEmbeddingsTexts();
        if (! is_array($texts)) {
            $texts = [$texts];
        }

        return collect($texts)
            ->map(fn (KnowledgeEmbeddingText $text) => new KnowledgeInsertItem(
                id: $knowledgeBaseId->id,
                entity: $text->entity,
                text: $text->text,
                payload: [
                    ...$this->toArray(),
                    'original_record_id' => $this->getKey(),
                ],
            ))
            ->toArray();
    }
}
