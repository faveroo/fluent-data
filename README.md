# Fluent Data

[![CI](https://github.com/faveroo/fluent-data/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/faveroo/fluent-data/actions/workflows/ci.yml)

`fluent-data` is a small PHP utility library focused on data-friendly building blocks:

- fluent collections
- DTO mapping with validation attributes
- array helpers with dot notation support
- string helpers for common transformations
- lightweight pipelines

## Install

```bash
composer require gabriel.hoffmann/fluent-data
```

## Collections

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

You can also use the `collect()` helper:

```php
$topTotals = collect([
    ['total' => 10],
    ['total' => 30],
    ['total' => 20],
])
    ->sortBy('total')
    ->take(-2)
    ->pluck('total')
    ->all();
```

Useful collection methods include:

- `map`, `filter`, `reject`, `reduce`
- `where`, `firstWhere`
- `sum`, `avg`
- `sortBy`, `groupBy`, `keyBy`, `unique`
- `take`, `chunk`, `pluck`, `contains`

## DTOs

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

$publicUser = $user
    ->masked(['email'])
    ->toArray();
```

You can also expose only the fields you want:

```php
$payload = $user
    ->only(['name'])
    ->toJson();
```

## Array Helpers

`Arr` helps read and reshape nested arrays with dot notation paths.

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

Useful array helper methods include:

- `get`, `has`, `set`, `forget`
- `only`, `except`
- `dot`, `undot`, `flatten`
- `first`, `last`, `contains`, `filter`, `pluck`

## String Helpers

`Str` provides helpers for identifiers, slicing and replacements.

```php
use Gabriel\FluentData\Facades\Str;

$slug = Str::slug('Olá Mundo PHP');
$column = Str::snake('createdAt');
$label = Str::kebab('UserProfileData');
$preview = Str::limit('Fluent data helpers', 12);

$domain = Str::after('ana@example.com', '@');
$greeting = Str::replace('Ana', 'Bruno', 'Olá Ana');
$uuid = Str::uuid();
```

Useful string helper methods include:

- `slug`, `studly`, `camel`, `snake`, `kebab`
- `startsWith`, `endsWith`, `contains`
- `before`, `after`, `between`, `limit`
- `replace`, `uuid`
- `random`, `randomize`, `ascii`, `binary`

## Pipelines

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

```bash
composer test
composer analyse
composer format:check
```

## Scope

This package intentionally stays focused on data utilities. It does not include router, controller, container, or database abstractions.
