<?php

namespace Gabriel\FluentData\Database;

use Gabriel\FluentData\Collections\Collection;

abstract class Repository
{
    protected string $table;

    public function __construct(
        protected Database $database
    ) {}

    public function query(): QueryBuilder
    {
        return $this->database->table($this->table);
    }

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function find(mixed $id): ?array
    {
        return $this->query()
            ->where('id', $id)
            ->first();
    }
}
