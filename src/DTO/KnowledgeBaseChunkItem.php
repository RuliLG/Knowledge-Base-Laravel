<?php

namespace Borah\KnowledgeBase\DTO;

use Illuminate\Contracts\Support\Arrayable;

class KnowledgeBaseChunkItem implements Arrayable
{
    public function __construct(
        public readonly mixed $id,
        public readonly string $text,
    ) {

    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
        ];
    }
}
