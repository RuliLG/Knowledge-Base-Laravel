<?php

namespace Borah\KnowledgeBase\Commands;

use Borah\KnowledgeBase\Client\KnowledgeBaseClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class KnowledgeBaseCommand extends Command
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
            $client = new KnowledgeBaseClient();
            $model::chunk(100, function ($records) use ($client) {
                $items = $records
                    ->map(fn ($item) => $item->knowledgeInsertItem())
                    ->toArray();
                $client->upsert($items);
            });
        });

        $this->comment('All done 🎉');

        return self::SUCCESS;
    }
}
