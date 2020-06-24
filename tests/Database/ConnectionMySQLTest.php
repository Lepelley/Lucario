<?php

namespace Lucario\Tests\Database;

use Lucario\Database\ConnectionMySQL;
use PHPUnit\Framework\TestCase;

class ConnectionMySQLTest extends TestCase
{
    public function testGetReturnPdoObject(): void
    {
        $this->assertInstanceOf(\PDO::class, ConnectionMySQL::get([
            'DSN' => 'sqlite::memory:',
            'LOGIN' => '',
            'PASSWORD' => '',
        ]));
    }

//    public function testGetCanThrowDatabaseException(): void
//    {
//        $this->expectException(DatabaseException::class);
//        $pdo = ConnectionMySQL::get([
//            'DSN' => 'mysql:host=localhost;dbname=test;port=3308',
//            'LOGIN' => '',
//            'PASSWORD' => '',
//        ]);
//        $pdo->exec('SELECT * FROM void');
//    }
}
