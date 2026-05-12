<?php

namespace Gabriel\FluentData\Contracts;

use Countable;
use IteratorAggregate;

interface Enumerable extends Countable, IteratorAggregate
{
    public function all(): array;

    public function map(callable $callback): static;

    public function filter(callable $callback): static;

    public function contains(mixed $value): bool;

    public function reject(callable $callback): static;

    public function reduce(callable $callback, mixed $init = null): mixed;

    public function first(): mixed;

    public function last(): mixed;

    public function each(callable $callback): static;

    public function pluck(string $key): static;
}
