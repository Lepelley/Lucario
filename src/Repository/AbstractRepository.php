<?php

namespace Lucario\Repository;

use Lucario\Database\DatabaseException;
use Lucario\Entity\AbstractEntity;

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
            if (false == $query) {
                throw new DatabaseException(sprintf(
                    'Something went wrong with your SQL request : %s',
                    json_encode($data)
                ));
            }
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
     * @return array|false
     *
     * @throws DatabaseException
     */
    public function findAll()
    {
        return $this->queryAndFetchAll("SELECT * FROM {$this->table}");
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);

        return;
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

    /**
     * @param string $sql
     * @param array<string,mixed> $params
     *
     * @return array|false
     *
     * @throws DatabaseException
     */
    protected function queryAndFetchAll($sql, $params = [])
    {
        $query = $this->pdo->prepare($sql);
        if (false == $query) {
            throw new DatabaseException(
                sprintf('Error during the creation of your query with the %s table', htmlspecialchars($this->table))
            );
        }
        $query->execute($params);

        if (null == $query || false == $query) {
            return [];
        }

        if ($this->entity) {
            $items = [];
            while ($item = $query->fetchObject($this->entity)) {
                $items[] = $item;
            }
        } else {
            $items = $query->fetchAll();
        }

        return $items;
    }
}
