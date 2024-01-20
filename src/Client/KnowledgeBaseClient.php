<?php

namespace Borah\KnowledgeBase\Client;

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
}
