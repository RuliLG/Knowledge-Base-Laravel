<?php

namespace Borah\KnowledgeBase\Contracts;

use Borah\KnowledgeBase\DTO\KnowledgeEmbeddingText;

interface Embeddable
{
    public function getEmbeddingsTexts(): KnowledgeEmbeddingText|array;
}
