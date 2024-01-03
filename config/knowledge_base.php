<?php

return [
    'connection' => [
        'host' => env('KNOWLEDGE_BASE_HOST', 'http://localhost:8100'),
    ],
    'models' => [
        'knowledge_base_id' => \Borah\KnowledgeBase\Models\KnowledgeBaseId::class,
    ],
];
