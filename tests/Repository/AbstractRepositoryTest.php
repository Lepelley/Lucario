<?php

namespace Lucario\Tests\Repository;

use Lucario\Database\ConnectionMySQL;
use Lucario\Database\DatabaseException;
use Lucario\Repository\AbstractRepository;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class AbstractRepositoryTest extends TestCase
{
    public function testCreateAndGetWithIdWithEntity(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $user = $repository->getWithId(1);
        $this->assertSame(['id' => '1', 'name' => 'Vincent'], ['id' => $user->getId(), 'name' => $user->getName()]);
    }

    public function testCreateAndGetWithIdWithoutEntity(): void
    {
        $repository = new class extends AbstractRepository{
            public function __construct()
            {
                $pdo = ConnectionMySQL::get([
                    'DSN' => 'sqlite::memory:',
                    'LOGIN' => '',
                    'PASSWORD' => '',
                ]);
                parent::__construct($pdo);
                $this->table = 'users';

                $pdo->exec(
                    'CREATE TABLE users (
                        id INT(6) NOT NULL,
                        name VARCHAR(255) NOT NULL
                    )'
                );
            }
        };
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $this->assertSame(['id' => '1', 'name' => 'Vincent'], $repository->getWithId(1));
    }

    public function testGetCanReturnFalse(): void
    {
        $repository = new UserRepository();
        $this->assertFalse($repository->getWithId(1));
    }

    public function testCreateCanThrowDatabaseException(): void
    {
        $repository = new UserRepository();
        $this->expectException(DatabaseException::class);
        var_dump($repository->create([]));
    }

    public function testFindAllReturnEmptyArray(): void
    {
        $repository = new UserRepository();
        $this->assertEmpty($repository->findAll());
    }

    public function testFindAllReturnEntitiesArray(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $repository->create(['id' => 2, 'name' => 'Kevin']);
        if (false === $items = $repository->findAll()) {
            $this->assertFalse(true);

            return;
        }
        $this->assertContainsOnlyInstancesOf(User::class, $items);
    }

    public function testFindAllCanReturnArrayOfArraysIfNoEntityDefined(): void
    {
        $repository = new class extends AbstractRepository{
            public function __construct()
            {
                $pdo = ConnectionMySQL::get([
                    'DSN' => 'sqlite::memory:',
                    'LOGIN' => '',
                    'PASSWORD' => '',
                ]);
                parent::__construct($pdo);
                $this->table = 'users';

                $pdo->exec(
                    'CREATE TABLE users (
                        id INT(6) NOT NULL,
                        name VARCHAR(255) NOT NULL
                    )'
                );
            }
        };
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $repository->create(['id' => 2, 'name' => 'Kevin']);
        $this->assertSame([
            ['id' => '1', 'name' => 'Vincent'],
            ['id' => '2', 'name' => 'Kevin'],
        ], $repository->findAll() ?? []);
    }

    public function testFindAllCanThrowDatabaseExceptionIfTableMistyped(): void
    {
        $repository = new class extends AbstractRepository{
            public function __construct()
            {
                $pdo = ConnectionMySQL::get([
                    'DSN' => 'sqlite::memory:',
                    'LOGIN' => '',
                    'PASSWORD' => '',
                ]);
                parent::__construct($pdo);
                $this->table = 'usersMistyped';

                $pdo->exec(
                    'CREATE TABLE users (
                        id INT(6) NOT NULL,
                        name VARCHAR(255) NOT NULL
                    )'
                );
            }
        };
        $this->expectException(DatabaseException::class);
        $this->assertEmpty($repository->findAll());
    }

    public function testDeleteWithBadRow(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $repository->delete(2);
        if (false === $items = $repository->findAll()) {
            $this->assertFalse(true);

            return;
        }
        $this->assertContainsOnlyInstancesOf(User::class, $items);
    }

    public function testDelete(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $repository->delete(1);
        if (false === $items = $repository->findAll()) {
            $this->assertFalse(true);

            return;
        }
        $this->assertEmpty($items);
    }
}
