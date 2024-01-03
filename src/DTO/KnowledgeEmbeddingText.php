<?php

namespace Borah\KnowledgeBase\DTO;

class KnowledgeEmbeddingText
{
    public function __construct(
        public readonly string $text,
        public readonly string $entity,
    ) {
        //
    }
}
