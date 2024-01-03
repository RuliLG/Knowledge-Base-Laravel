<?php

namespace Borah\KnowledgeBase\Commands;

use Borah\KnowledgeBase\Facades\KnowledgeBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class ReimportKnowledgeBaseCommand extends Command
{
    public $signature = 'knowledge-base:reimport {model?}';

    public $description = 'Runs the import command on all the models of the app.';

    public function handle(): int
    {
        $models = collect(File::allFiles(base_path('app/Models')))
            ->map(fn (SplFileInfo $file) => 'App\\Models\\'.$file->getBasename('.php'))
            ->filter(fn (string $model) => class_exists($model) && is_subclass_of($model, \Borah\KnowledgeBase\Contracts\Embeddable::class))
            ->when($this->argument('model'), fn ($collection) => $collection->filter(fn (string $model) => $model === $this->argument('model')))
            ->values();

        if ($models->isEmpty()) {
            $this->error('No models found');

            return self::FAILURE;
        }

        $models->each(function (string $model) {
            $this->info("Importing {$model}");
            $model::chunk(100, function ($records) {
                dispatch(function () use ($records) {
                    KnowledgeBase::upsert($records);
                });
            });
        });

        $this->comment('All done 🎉');

        return self::SUCCESS;
    }
}
