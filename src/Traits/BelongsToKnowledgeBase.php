<?php

namespace Borah\KnowledgeBase\Traits;

use Borah\KnowledgeBase\Client\KnowledgeBaseClient;
use Borah\KnowledgeBase\DTO\KnowledgeInsertItem;
use Illuminate\Database\Eloquent\Model;

use function Illuminate\Events\queueable;

trait BelongsToKnowledgeBase
{
    public static function bootBelongsToKnowledgeBase()
    {
        static::created(queueable(function (Model $model) {
            $client = new KnowledgeBaseClient();
            $client->upsert([$model->knowledgeInsertItem()]);
        }));

        static::updated(queueable(function (Model $model) {
            $client = new KnowledgeBaseClient();
            $client->upsert([$model->knowledgeInsertItem()]);
        }));

        static::deleted(queueable(function (Model $model) {
            $client = new KnowledgeBaseClient();
            $client->destroy($model->knowledgeInsertItem()->id);
        }));
    }

    public function knowledgeInsertItem(): KnowledgeInsertItem
    {
        return new KnowledgeInsertItem(
            id: class_basename($this).'_'.$this->getKey(),
            entity: class_basename($this),
            text: $this->getEmbeddingsText(),
            payload: $this->toArray(),
        );
    }
}
