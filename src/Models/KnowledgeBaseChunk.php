<?php

namespace Borah\KnowledgeBase\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseChunk extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'model_type',
        'model_id',
        'text',
    ];

    public $timestamps = false;

    public function model()
    {
        return $this->morphTo();
    }
}
