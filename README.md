# Laravel wrapper for the Knowledge-Base API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/borah/knowledge-base-laravel.svg?style=flat-square)](https://packagist.org/packages/borah/knowledge-base-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/borah/knowledge-base-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/borah/knowledge-base-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/borah/knowledge-base-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/borah/knowledge-base-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/borah/knowledge-base-laravel.svg?style=flat-square)](https://packagist.org/packages/borah/knowledge-base-laravel)

This package is a Laravel wrapper for the [Knowledge Base API](https://github.com/RuliLG/Knowledge-Base). It will create and maintain a Knowledge Base for all the configured Eloquent models.

## Installation

You can install the package via composer:

```bash
composer require borah/knowledge-base-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="knowledge-base-laravel-config"
```

This is the contents of the published config file:

```php
return [
    'connection' => [
        'host' => env('KNOWLEDGE_BASE_HOST', 'http://localhost:8100'),
    ],
];
```

## Requirements

This package is a wrapper for [Knowledge Base API](https://github.com/RuliLG/Knowledge-Base), so you need to have it running in order to use this package.

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

## Credits

- [Raúl López](https://github.com/RuliLG)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
