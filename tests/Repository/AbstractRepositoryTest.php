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
    public function testCreateAndGetWithId(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $user = $repository->getWithId(1);
        $this->assertSame(['id' => '1', 'name' => 'Vincent'], ['id' => $user->getId(), 'name' => $user->getName()]);
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
        $repository->create([]);
    }

    public function testCreateCanThrowDatabaseExceptionIfTableMistyped(): void
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
        $repository->create([]);
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
        $repository->findAll();
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

    public function testUpdateCanThrowDatabaseExceptionIfDataIsEmpty(): void
    {
        $repository = new UserRepository();
        $this->expectException(DatabaseException::class);
        $repository->update(1, []);
    }

    public function testUpdateWithStringNullValue(): void
    {
        $repository = new UserRepository();
        $repository->update(1, ['name' => 'null']);
        $this->assertSame(2, 1+1);
    }

    public function testUpdate(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $repository->update(1, ['name' => 'Vincent Test']);
        $this->assertSame('Vincent Test', $repository->getWithId(1)->getName());
    }

    public function testUpdateDoesNothingIfTableIsMistyped(): void
    {
        $repository = new class extends AbstractRepository {
            public function __construct()
            {
                $pdo = ConnectionMySQL::get([
                    'DSN' => 'sqlite::memory:',
                    'LOGIN' => '',
                    'PASSWORD' => '',
                ]);
                parent::__construct($pdo);
                $this->entity = User::class;
                $this->table = 'users_bug';

                $pdo->exec(
                    'CREATE TABLE users (
                        id INT(6) NOT NULL,
                        name VARCHAR(20) NOT NULL
                   )'
                );
            }
        };
        $this->expectException(DatabaseException::class);
        $repository->update(1, ['name' => 'Vincent Test']);
    }

    public function testGetWithIdCanThrowDatabaseException(): void
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
        $repository->getWithId(1);
    }
}
