<?php

namespace Borah\KnowledgeBase\DTO;

use Borah\KnowledgeBase\Models\KnowledgeBaseChunk;
use Illuminate\Database\Eloquent\Collection;

class KnowledgeBaseQueryResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly array $results,
    ) {
        //
    }

    public static function from(array $response): static
    {
        return new static(
            success: $response['success'],
            results: array_map(fn ($result) => KnowledgeBaseQueryResponseItem::from($result), $response['results']),
        );
    }

    public function models(): Collection
    {
        $ids = collect($this->results)->pluck('id');
        $intermediateRecords = KnowledgeBaseChunk::query()
            ->with('model')
            ->whereIn('id', $ids)
            ->get();

        // sort the records in the same order as the results
        $intermediateRecords = $intermediateRecords->sortBy(fn ($record) => array_search($record->id, $ids->toArray()));

        return $intermediateRecords
            ->map(fn ($record) => $record->model)
            ->unique();
    }
}
