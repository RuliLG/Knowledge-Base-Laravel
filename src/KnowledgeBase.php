<?php

namespace Borah\KnowledgeBase;

use Borah\KnowledgeBase\Client\KnowledgeBaseClient;
use Borah\KnowledgeBase\DTO\KnowledgeBaseQueryResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class KnowledgeBase
{
    public function upsertOne(Model $model): bool
    {
        $client = new KnowledgeBaseClient();

        return $client->upsert($model->knowledgeInsertItems());
    }

    public function upsert(Collection $models): bool
    {
        $client = new KnowledgeBaseClient();
        $data = $models
            ->map(fn (Model $model) => $model->knowledgeInsertItems())
            ->flatten(1)
            ->toArray();

        return $client->upsert($data);
    }

    public function destroy(Model $model): bool
    {
        $client = new KnowledgeBaseClient();
        $items = $model->knowledgeInsertItems();

        foreach ($items as $item) {
            $client->destroy($item->id);
        }

        return true;
    }

    public function query(string $text, int $k = 10, ?array $entities = null, ?array $where = null): KnowledgeBaseQueryResponse
    {
        $client = new KnowledgeBaseClient();

        return $client->query($text, $k, $entities, $where);
    }

    /**
     * Generates chunks of text for the selected records.
     *
     * @param array<\Borah\KnowledgeBase\DTO\KnowledgeBaseChunkItem> $records
     *
     * @return array<\Borah\KnowledgeBase\DTO\KnowledgeBaseChunkItem>
     */
    public function chunk(array $records, ?int $chunkSize = null, ?int $chunkOverlap = null): array
    {
        $client = new KnowledgeBaseClient();

        return $client->chunk($records, $chunkSize, $chunkOverlap);
    }
}
