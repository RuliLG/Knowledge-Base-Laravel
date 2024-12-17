# Laravel wrapper for the Knowledge-Base API

This package is a Laravel wrapper for the [Knowledge Base API](https://github.com/BorahLabs/Knowledge-Base). It will create and maintain a Knowledge Base for all the configured Eloquent models.

## Installation

You can install the package via composer:

```bash
composer require borah/knowledge-base-laravel
```

You must publish the migrations:

```bash
php artisan vendor:publish --tag="knowledge-base-laravel-migrations"
```

After the migration has been published you can run them using `php artisan migrate`.

Also, if you want, you can publish the config file with:

```bash
php artisan vendor:publish --tag="knowledge-base-laravel-config"
```

This is the contents of the published config file:

```php
return [
    'connection' => [
        'host' => env('KNOWLEDGE_BASE_HOST', 'http://localhost:8100'),
    ],
    'models' => [
        'knowledge_base_id' => \Borah\KnowledgeBase\Models\KnowledgeBaseId::class,
    ],
];

```

## Requirements

This package is a wrapper for [Knowledge Base API](https://github.com/BorahLabs/Knowledge-Base), so you need to have it running in order to use this package.

## Usage

To use it, you need to add the `Borah\KnowledgeBase\Traits\HasKnowledgeBase` trait to the models you want to add to the Knowledge Base. Also, these models should implement the `Borah\KnowledgeBase\Contracts\Embeddable` interface.

For example:

```php
<?php

namespace App\Models;

use Borah\KnowledgeBase\Contracts\Embeddable;
use Borah\KnowledgeBase\DTO\KnowledgeEmbeddingText;
use Borah\KnowledgeBase\Traits\BelongsToKnowledgeBase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model implements Embeddable
{
    use HasFactory;
    use BelongsToKnowledgeBase;

    public function getEmbeddingsTexts(): KnowledgeEmbeddingText|array
    {
        return [
            new KnowledgeEmbeddingText(
                text: $this->content,
                entity: class_basename($this),
            ),
        ];
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Borah Digital Labs
[Borah Digital Labs](https://borah.digital/) crafts web applications, open-source packages, and offers a team of full-stack solvers ready to tackle your next project. We have built a series of projects:

- [CodeDocumentation](https://codedocumentation.app/): Automatic code documentation platform
- [AutomaticDocs](https://automaticdocs.app/): One-time documentation for your projects
- [Talkzy](https://talkzy.app/): A tool to summarize meetings
- Compass: An agent-driven tool to help manage companies more efficiently
- [Prompt Token Counter](https://prompttokencounter.com/): Simple tool to count tokens in prompts
- [Sabor en la Oficina](https://saborenlaoficina.es/): Website + catering management platform

We like to use Laravel for most of our projects and we love to tackle big, complicated problems. Feel free to reach out and we can have a virtual coffee!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

