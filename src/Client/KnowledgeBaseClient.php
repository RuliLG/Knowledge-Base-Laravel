<?php

namespace Borah\KnowledgeBase\Client;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class KnowledgeBaseClient
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('knowledge-base.connection.host'))
            ->withHeaders([
                'Accept' => 'application/json',
            ]);
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
}
