# Fluent Data

[![CI](https://github.com/faveroo/fluent-data/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/faveroo/fluent-data/actions/workflows/ci.yml)

`fluent-data` is a small PHP utility package for shaping, validating and moving data through expressive PHP code.

It brings together:

- fluent collections
- DTO mapping with validation attributes
- array helpers with dot notation support
- string helpers for common transformations
- lightweight pipelines

## Installation

```shell
composer require gabriel.hoffmann/fluent-data
```

## Collections

Use collections to filter, transform, group and aggregate lists without losing readability.

```php
use Gabriel\FluentData\Collections\Collection;

$orders = Collection::make([
    ['customer' => 'Ana', 'status' => 'paid', 'total' => 120],
    ['customer' => 'Bruno', 'status' => 'pending', 'total' => 80],
    ['customer' => 'Ana', 'status' => 'paid', 'total' => 60],
]);

$totalPaid = $orders
    ->where('status', 'paid')
    ->sum('total');

$ordersByCustomer = $orders
    ->groupBy('customer')
    ->toArray();
```

The global `collect()` helper is also available:

```php
$topCustomers = collect([
    ['name' => 'Ana', 'score' => 92],
    ['name' => 'Bruno', 'score' => 75],
    ['name' => 'Clara', 'score' => 88],
])
    ->sortBy('score')
    ->take(-2)
    ->pluck('name')
    ->values()
    ->all();
```

## DTOs

Create typed data objects from arrays and validate them with attributes.

```php
use Gabriel\FluentData\DTO\Attributes\Email;
use Gabriel\FluentData\DTO\Attributes\Min;
use Gabriel\FluentData\DTO\Attributes\Required;
use Gabriel\FluentData\DTO\Data\Data;

class UserData extends Data
{
    #[Required]
    #[Min(3)]
    protected string $name;

    #[Required]
    #[Email]
    protected string $email;
}

$user = UserData::fromArray([
    'name' => 'Ana',
    'email' => 'ana@example.com',
]);

return $user
    ->masked(['email'])
    ->toArray();
```

You can also expose only the fields you want:

```php
$publicProfile = $user
    ->only(['name'])
    ->toJson();
```

## Array Helpers

`Arr` helps read and reshape nested arrays, including dot notation paths.

```php
use Gabriel\FluentData\Facades\Arr;

$payload = [
    'user' => [
        'profile' => [
            'name' => 'Ana',
            'email' => 'ana@example.com',
        ],
    ],
];

$name = Arr::get($payload, 'user.profile.name');

$payload = Arr::set($payload, 'user.profile.active', true);

$publicPayload = Arr::except($payload, [
    'user.profile.email',
]);

$flatPayload = Arr::dot($publicPayload);
```

Useful methods include:

- `get`, `has`, `set`, `forget`
- `only`, `except`
- `dot`, `undot`, `flatten`
- `first`, `last`, `contains`, `filter`, `pluck`

## String Helpers

`Str` provides small transformations for identifiers, slugs and string slicing.

```php
use Gabriel\FluentData\Facades\Str;

$slug = Str::slug('Olá Mundo PHP');
$className = Str::studly('user-profile-data');
$columnName = Str::snake('createdAt');
$preview = Str::limit('Fluent data helpers', 12);

$domain = Str::after('ana@example.com', '@');
$token = Str::between('token:[abc123]', '[', ']');
$hasName = Str::has('Hello Ana', 'ana', 1);
```

Useful methods include:

- `slug`, `studly`, `camel`, `snake`, `kebab`
- `startsWith`, `endsWith`, `contains`, `has`
- `before`, `after`, `between`, `limit`
- `random`, `randomize`, `ascii`, `binary`

## Pipelines

Pipelines let you pass data through small transformation classes.

```php
use Gabriel\FluentData\Pipeline\Pipeline;

class TrimName
{
    public function handle(array $payload, callable $next): mixed
    {
        $payload['name'] = trim($payload['name']);

        return $next($payload);
    }
}

class UpperName
{
    public function handle(array $payload, callable $next): mixed
    {
        $payload['name'] = strtoupper($payload['name']);

        return $next($payload);
    }
}

$result = Pipeline::make(['name' => '  ana  '])
    ->through([TrimName::class, UpperName::class])
    ->then(fn (array $payload) => $payload);
```

## Helpers

Global helpers:

- `collect($items)`
- `tap($value, $callback)`
- `value($value)`
- `dd(...$vars)`

Support classes and facades:

- `Gabriel\FluentData\Support\Arr`
- `Gabriel\FluentData\Support\Str`
- `Gabriel\FluentData\Facades\Arr`
- `Gabriel\FluentData\Facades\Str`

## Quality

```shell
composer test
composer analyse
composer format:check
```

## Scope

This package intentionally stays focused on data utilities. It does not include router, controller, container, or database abstractions.
