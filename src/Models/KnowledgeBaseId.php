<?php

namespace Borah\KnowledgeBase\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseId extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'model_type',
        'model_id',
    ];

    public $timestamps = false;
}
