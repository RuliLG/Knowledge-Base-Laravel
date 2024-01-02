<?php

namespace RuliLG\KnowledgeBase;

use RuliLG\KnowledgeBase\Commands\KnowledgeBaseCommand;
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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_knowledge-base-laravel_table')
            ->hasCommand(KnowledgeBaseCommand::class);
    }
}
