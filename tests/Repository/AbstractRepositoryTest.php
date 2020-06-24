<?php

namespace Lucario\Tests\Repository;

use Lucario\Database\ConnectionMySQL;
use Lucario\Database\DatabaseException;
use Lucario\Repository\AbstractRepository;
use PHPUnit\Framework\TestCase;

class AbstractRepositoryTest extends TestCase
{
    public function testCreateAndGetWithIdWithEntity(): void
    {
        $repository = new UserRepository();
        $repository->create(['id' => 1, 'name' => 'Vincent']);
        $user = $repository->getWithId(1);
//        $this->assertSame(['id' => '1', 'name' => 'Vincent'], $user);
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

    /**
     * @runInSeparateProcess
     */
    public function testGetCanReturnFalse(): void
    {
        $repository = new UserRepository();
        $this->assertFalse($repository->getWithId(1));
    }

//    /**
//     * @runInSeparateProcess
//     */
//    public function testCreateCanThrowDatabaseException(): void
//    {
//        $repository = new class extends AbstractRepository{
//            public function __construct()
//            {
//                $pdo = ConnectionMySQL::get([
//                    'DSN' => 'sqlite::memory:',
//                    'LOGIN' => '',
//                    'PASSWORD' => '',
//                ]);
//                parent::__construct($pdo);
//                $this->table = 'users';
//            }
//        };
//        $this->expectException(DatabaseException::class);
//        var_dump($repository->create(['id' => 1, 'name' => 'Test']));
//    }
}
