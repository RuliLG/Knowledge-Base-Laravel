<?php

namespace Borah\KnowledgeBase;

use Borah\KnowledgeBase\Commands\KnowledgeBaseCommand;
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
            ->hasConfigFile('knowledge-base')
            ->hasCommand(KnowledgeBaseCommand::class);
    }
}
