<?php

namespace Borah\KnowledgeBase;

use Borah\KnowledgeBase\Client\KnowledgeBaseClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class KnowledgeBase
{
    public function upsertOne(Model $model): bool
    {
        $client = new KnowledgeBaseClient();

        return $client->upsert([$model->knowledgeInsertItem()]);
    }

    public function upsert(Collection $models): bool
    {
        $client = new KnowledgeBaseClient();
        $data = $models->map(fn (Model $model) => $model->knowledgeInsertItem())->toArray();

        return $client->upsert($data);
    }

    public function destroy(Model $model): bool
    {
        $client = new KnowledgeBaseClient();

        return $client->destroy($model->knowledgeInsertItem()->id);
    }
}
