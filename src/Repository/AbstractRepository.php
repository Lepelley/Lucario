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
        return $this->queryAndFetchOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * @param int                  $id
     * @param array<string,string> $data
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function update(int $id, array $data): void
    {
        $sqlFields = [];
        foreach ($data as $key => &$value) {
            $sqlFields[] = "$key = :$key";
            if ('null' === $value) {
                $value = null;
            }
        }

        try {
            $query = $this->pdo->prepare("UPDATE {$this->table} SET ".implode(', ', $sqlFields)." WHERE id = :id");
            $query->execute(array_merge($data, ['id' => $id]));
        } catch (\PDOException $error) {
            throw new DatabaseException(
                \sprintf("Impossible to update a record in {$this->table} table : %s", $error->getMessage())
            );
        }
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
        try {
            $query = $this->pdo->prepare($sql);
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
            $query->execute($params);

            return $query->fetchAll();
        } catch (\PDOException $error) {
            throw new DatabaseException(
                \sprintf("Impossible to update a record in {$this->table} table : %s", $error->getMessage())
            );
        }
    }

    /**
     * @param string $sql
     * @param array<string,mixed> $params
     *
     * @return array|false
     *
     * @throws DatabaseException
     */
    protected function queryAndFetchOne($sql, $params = [])
    {
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute($params);

            return $query->fetchObject($this->entity);
        } catch (\PDOException $exception) {
            throw new DatabaseException(
                \sprintf("Impossible to update a record in {$this->table} table : %s", $exception->getMessage())
            );
        }
    }
}
