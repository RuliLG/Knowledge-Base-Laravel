<?php

return [
    'connection' => [
        'host' => env('KNOWLEDGE_BASE_HOST', 'http://localhost:8100'),
        'timeout' => env('KNOWLEDGE_BASE_TIMEOUT', 90),
    ],
    'models' => [
        'knowledge_base_id' => \Borah\KnowledgeBase\Models\KnowledgeBaseId::class,
    ],
];
