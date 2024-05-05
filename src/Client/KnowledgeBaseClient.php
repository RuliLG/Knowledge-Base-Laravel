<?php

namespace Borah\KnowledgeBase\Client;

use Borah\KnowledgeBase\DTO\KnowledgeBaseChunkItem;
use Borah\KnowledgeBase\DTO\KnowledgeBaseQueryResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class KnowledgeBaseClient
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('knowledge_base.connection.host'))
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->timeout(config('knowledge_base.connection.timeout'));
    }

    /**
     * Upserts a list of records into the knowledge base.
     *
     * @param  array<\Borah\KnowledgeBase\DTO\KnowledgeInsertItem>  $data
     * @return bool Whether the operation was successful or not.
     */
    public function upsert(array $data): bool
    {
        logger()->debug('[KnowledgeBaseClient] Upserting data', $data);
        return $this->client->post('/insert', [
            'data' => $data,
        ])
            ->throw()
            ->json('success') ?: false;
    }

    /**
     * Deletes a record from the knowledge base
     *
     *
     * @return bool Whether the operation was successful or not.
     */
    public function destroy(mixed $id): bool
    {
        logger()->debug('[KnowledgeBaseClient] Deleting record', ['id' => $id]);
        return $this->client->delete('/delete/'.$id)
            ->throw()
            ->json('success') ?: false;
    }

    /**
     * Searches the knowledge base for a given text.
     *
     * @param  string  $text The text to search for.
     * @param  int  $k The number of results to return.
     * @param  array<string>  $entities The entities to search for.
     * @param  array<string:string>  $where The entities to search for.
     */
    public function query(string $text, int $k = 10, ?array $entities = null, ?array $where = null): KnowledgeBaseQueryResponse
    {
        logger()->debug('[KnowledgeBaseClient] Querying knowledge base', [
            'text' => $text,
            'k' => $k,
            'entities' => $entities,
            'where' => $where,
        ]);
        $params = [
            'query' => $text,
            'k' => $k,
        ];

        if ($entities) {
            $params['entities'] = implode(',', $entities);
        }

        if ($where) {
            $params['where'] = json_encode($where);
        }

        $response = $this->client->get('/query', $params)
            ->throw()
            ->json();

        return KnowledgeBaseQueryResponse::from($response);
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
        logger()->debug('[KnowledgeBaseClient] Chunking data');
        $response = $this->client->post('/chunk', [
            'data' => $records,
            'chunk_size' => $chunkSize ?? config('knowledge_base.chunking.size'),
            'chunk_overlap' => $chunkOverlap ?? config('knowledge_base.chunking.overlap'),
        ])
            ->throw()
            ->collect('chunks');

        return $response->map(fn (array $item) => new KnowledgeBaseChunkItem(
            id: $item['id'],
            text: $item['text'],
        ))->all();
    }
}
