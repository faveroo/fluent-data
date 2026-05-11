<?php 

namespace Gabriel\FluentData\Database;

use Gabriel\FluentData\Collections\Collection;

class QueryBuilder
{
    protected array $wheres = [];

    public function __construct(
        protected Database $database,
        protected string $table
    ) {}

    public function where(string $column, mixed $operator, mixed $value = null): static
    {
        if(func_num_args() == 2)
        {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function get(): Collection
    {
        [$sql, $bindings] = $this->toSql();
        return $this->database->select($sql, $bindings);
    }

    public function first(): ?array
    {
        return $this->get()->first() ?: null;
    }

    public function insert(array $data): bool
    {
        $columns = array_keys($data);

        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->table,
            implode(', ', $columns),
            ':' . implode(', :', $columns)
        );

        return $this->database->statement($sql, $data);
    }

     public function toSql(): array
    {
        $sql = "select * from {$this->table}";
        $bindings = [];

        if ($this->wheres) {
            $parts = [];

            foreach ($this->wheres as $index => $where) {
                $key = "where_{$index}";

                $parts[] = "{$where['column']} {$where['operator']} :{$key}";
                $bindings[$key] = $where['value'];
            }

            $sql .= ' where ' . implode(' and ', $parts);
        }

        return [$sql, $bindings];
    }
}