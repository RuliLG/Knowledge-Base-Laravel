<?php

namespace Borah\KnowledgeBase\DTO;

class KnowledgeBaseQueryResponseItem
{
    public function __construct(
        public readonly mixed $id,
        public readonly string $entity,
        public readonly string $text,
        public readonly float $rerankingScore,
        public readonly ?array $payload = null,
    ) {
        //
    }

    public static function from(array $result): static
    {
        return new static(
            id: $result['id'],
            entity: $result['entity'],
            text: $result['text'],
            rerankingScore: $result['reranking_score'],
            payload: $result['payload'] ?? null,
        );
    }
}
