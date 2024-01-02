<?php

namespace RuliLG\KnowledgeBase\Commands;

use Illuminate\Console\Command;

class KnowledgeBaseCommand extends Command
{
    public $signature = 'knowledge-base-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
