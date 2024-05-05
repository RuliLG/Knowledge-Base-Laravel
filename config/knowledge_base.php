<?php

return [
    'connection' => [
        'host' => env('KNOWLEDGE_BASE_HOST', 'http://localhost:8100'),
        'timeout' => env('KNOWLEDGE_BASE_TIMEOUT', 90),
    ],
    'models' => [
        'knowledge_base_chunk' => \Borah\KnowledgeBase\Models\KnowledgeBaseChunk::class,
    ],
    'chunking' => [
        'size' => env('KNOWLEDGE_BASE_CHUNK_SIZE', 1000),
        'overlap' => env('KNOWLEDGE_BASE_CHUNK_OVERLAP', 100),
    ],
];
