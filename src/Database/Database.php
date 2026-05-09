<?php 

namespace Gabriel\FluentData\Database;

use Gabriel\FluentData\Collections\Collection;
use PDO;

class Database
{
    protected PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'],
            $config['port'] ?? '3306',
            $config['database'],
            $config['charset'] ?? 'utf8mb4',
        );

        $this->pdo = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public function table(string $table): QueryBuilder
    {
        return new QueryBuilder($this, $table);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function statement(string $sql, array $params = []): bool
    {
        $statement = $this->pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function affectingStatement(string $sql, array $params = []): int
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    public function select(string $sql, array $params = []): Collection
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return collect($statement->fetchAll());
    }
}
