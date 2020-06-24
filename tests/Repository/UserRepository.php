<?php

namespace Lucario\Tests\Repository;

use Lucario\Database\ConnectionMySQL;
use Lucario\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function __construct()
    {
        $pdo = ConnectionMySQL::get([
            'DSN' => 'sqlite::memory:',
            'LOGIN' => '',
            'PASSWORD' => '',
        ]);
        parent::__construct($pdo);
        $this->entity = User::class;
        $this->table = 'users';

        $pdo->exec(
            'CREATE TABLE users (
                id INT(6) NOT NULL,
                name VARCHAR(255) NOT NULL
            )'
        );
    }
}
