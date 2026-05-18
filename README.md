# Fluent Data

[![CI](https://github.com/faveroo/fluent-data/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/faveroo/fluent-data/actions/workflows/ci.yml)

`fluent-data` is a small PHP utility library focused on data-friendly building blocks:

- collections
- DTO mapping and validation attributes
- array and string helpers
- simple pipelines

## Install

```bash
composer require gabriel.hoffmann/fluent-data
```

## Collections

```php
use Gabriel\FluentData\Collections\Collection;

$users = Collection::make([
    ['name' => 'Ana'],
    ['name' => 'Bruno'],
]);

$names = $users->pluck('name');
```

You can also use the `collect()` helper:

```php
$numbers = collect([1, 2, 3, 4])
    ->reject(fn (int $number) => $number % 2 === 0);
```

## DTOs

```php
use Gabriel\FluentData\DTO\Attributes\Email;
use Gabriel\FluentData\DTO\Attributes\Required;
use Gabriel\FluentData\DTO\Data\Data;

class UserData extends Data
{
    #[Required]
    protected string $name;

    #[Required]
    #[Email]
    protected string $email;
}

$user = UserData::fromArray([
    'name' => 'Ana',
    'email' => 'ana@example.com',
]);

echo $user->masked(['email'])->toJson();
```

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

- `Gabriel\FluentData\Support\Arr`
- `Gabriel\FluentData\Support\Str`
- `Gabriel\FluentData\Facades\Arr`
- `Gabriel\FluentData\Facades\Str`
- global helpers `collect()`, `dd()`, `tap()` and `value()`

## Scope

This package intentionally stays focused on data utilities. It does not include router, controller, container, or database abstractions.
