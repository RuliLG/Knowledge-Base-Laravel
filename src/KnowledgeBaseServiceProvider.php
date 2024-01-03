<?php

namespace Borah\KnowledgeBase;

use Borah\KnowledgeBase\Commands\ReimportKnowledgeBaseCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class KnowledgeBaseServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('knowledge-base-laravel')
            ->hasConfigFile('knowledge_base')
            ->hasMigration('2024_01_01_000000_create_knowledge_base_ids_table')
            ->hasCommand(ReimportKnowledgeBaseCommand::class);
    }
}
