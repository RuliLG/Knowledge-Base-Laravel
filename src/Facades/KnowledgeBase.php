<?php

namespace RuliLG\KnowledgeBase\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RuliLG\KnowledgeBase\KnowledgeBase
 */
class KnowledgeBase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RuliLG\KnowledgeBase\KnowledgeBase::class;
    }
}
