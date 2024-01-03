<?php

namespace Borah\KnowledgeBase\DTO;

use Illuminate\Contracts\Support\Arrayable;

class KnowledgeInsertItem implements Arrayable
{
    public function __construct(
        public readonly mixed $id,
        public readonly string $entity,
        public readonly string $text,
        public readonly ?array $payload = null,
    ) {

    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'entity' => $this->entity,
            'text' => $this->text,
            'payload' => $this->payload,
        ];
    }
}
