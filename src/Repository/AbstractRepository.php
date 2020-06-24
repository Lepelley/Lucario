<?php

namespace Lucario\Repository;

use Lucario\Database\DatabaseException;

abstract class AbstractRepository
{
    protected \PDO $pdo;
    protected string $table;
    protected string $entity;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->table = '';
        $this->entity = '';
    }

    /**
     * @param array<string,mixed> $data
     *
     * @return int|string ID of the created entity
     *
     * @throws DatabaseException
     */
    public function create(array $data)
    {
        $values = \implode(', ', \array_map(fn ($key) => ":$key", \array_keys($data)));

        $fields = \implode(', ', \array_keys($data));

        try {
            $query = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
            $query->execute($data);
            $id = $this->pdo->lastInsertId();

            return $id ?? null;
        } catch (\PDOException $error) {
            throw new DatabaseException(
                sprintf(
                    "Impossible to create a record in %s table : %s {$error->getMessage()}",
                    $this->table,
                    $error->getMessage()
                )
            );
        }
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getWithId(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);

        if ($this->entity) {
            return $query->fetchObject($this->entity) ?? false;
        }

        return $query->fetch() ?? false;
    }
}
