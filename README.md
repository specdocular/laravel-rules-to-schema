# Laravel Rules to Schema

[![Tests](https://github.com/specdocular/laravel-rules-to-schema/actions/workflows/tests.yml/badge.svg)](https://github.com/specdocular/laravel-rules-to-schema/actions/workflows/tests.yml)
[![Code Style](https://github.com/specdocular/laravel-rules-to-schema/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/specdocular/laravel-rules-to-schema/actions/workflows/php-cs-fixer.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/specdocular/laravel-rules-to-schema.svg)](https://packagist.org/packages/specdocular/laravel-rules-to-schema)
[![PHP Version](https://img.shields.io/packagist/php-v/specdocular/laravel-rules-to-schema.svg)](https://packagist.org/packages/specdocular/laravel-rules-to-schema)
[![License](https://img.shields.io/packagist/l/specdocular/laravel-rules-to-schema.svg)](https://packagist.org/packages/specdocular/laravel-rules-to-schema)

Convert Laravel validation rules into [JSON Schema Draft 2020-12](https://json-schema.org/draft/2020-12/json-schema-core) definitions.

## Installation

```bash
composer require specdocular/laravel-rules-to-schema
```

The service provider is auto-discovered by Laravel.

## Usage

```php
use Specdocular\LaravelRulesToSchema\RuleToSchema;
use Specdocular\LaravelRulesToSchema\ValidationRuleNormalizer;

$converter = app(RuleToSchema::class);

$rules = [
    'email' => ['required', 'email', 'max:255'],
    'age' => ['required', 'integer', 'min:18', 'max:120'],
    'tags' => ['array'],
    'tags.*' => ['string', 'max:50'],
];

$normalizer = new ValidationRuleNormalizer();
$normalized = $normalizer->normalize($rules);

$schema = $converter->transform($normalized);
$compiled = $schema->compile();
```

### Configuration

Publish the config file to customize rule parsers:

```bash
php artisan vendor:publish --tag=rules-to-schema-config
```

## Features

- Converts 25+ built-in Laravel validation rules to JSON Schema
- Handles nested objects, arrays, and wildcard (`*`) rules
- Supports conditional rules (`required_if`, `required_with`, etc.)
- Extensible — register custom rule parsers via config
- Auto-registered as a Laravel service provider

## Related Packages

| Package | Description |
|---------|-------------|
| [specdocular/php-json-schema](https://github.com/specdocular/php-json-schema) | JSON Schema Draft 2020-12 builder (foundation) |
| [specdocular/php-openapi](https://github.com/specdocular/php-openapi) | Object-oriented OpenAPI builder |
| [specdocular/laravel-openapi](https://github.com/specdocular/laravel-openapi) | Laravel integration for OpenAPI generation |

## License

MIT. See [LICENSE](LICENSE) for details.
