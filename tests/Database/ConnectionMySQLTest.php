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
}
