<?php

namespace Gabriel\FluentData\Database;

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

    public function all()
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
