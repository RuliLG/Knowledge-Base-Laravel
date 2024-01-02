<?php

namespace Borah\KnowledgeBase\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Borah\KnowledgeBase\KnowledgeBase
 */
class KnowledgeBase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Borah\KnowledgeBase\KnowledgeBase::class;
    }
}
