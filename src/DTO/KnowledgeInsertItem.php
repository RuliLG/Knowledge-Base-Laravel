<?php

namespace Borah\KnowledgeBase\DTO;

use Illuminate\Contracts\Support\Arrayable;

class KnowledgeInsertItem implements Arrayable
{
    public function __construct(
        public mixed $id,
        public string $entity,
        public string $text,
        public ?array $payload = null,
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
