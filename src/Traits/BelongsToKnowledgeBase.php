<?php

namespace Borah\KnowledgeBase\Traits;

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
            KnowledgeBase::upsert($model);
        }));

        static::updated(queueable(function (Model $model) {
            KnowledgeBase::upsert($model);
        }));

        static::deleted(queueable(function (Model $model) {
            KnowledgeBase::destroy($model);
        }));
    }

    public function knowledgeBaseId(): MorphOne
    {
        return $this->morphOne(config('knowledge_base.models.knowledge_base_id'), 'model');
    }

    public function knowledgeInsertItem(): KnowledgeInsertItem
    {
        $knowledgeBaseId = $this->knowledgeBaseId ?? $this->knowledgeBaseId()->create();

        return new KnowledgeInsertItem(
            id: $knowledgeBaseId->id,
            entity: class_basename($this),
            text: $this->getEmbeddingsText(),
            payload: [
                ...$this->toArray(),
                'original_record_id' => $this->getKey(),
            ],
        );
    }
}
