<?php

namespace Lucario\Database;

class ConnectionMySQL
{
    private static ?\PDO $pdo = null;

    /**
     * @param array<string,string> $credentials
     *
     * @return \PDO
     */
    public static function get(array $credentials): \PDO
    {
        $dsn = isset($credentials['DSN']) ? $credentials['DSN'] : '';
        $login = isset($credentials['LOGIN']) ? $credentials['LOGIN'] : '';
        $password = isset($credentials['PASSWORD']) ? $credentials['PASSWORD'] : '';

        if (null === self::$pdo) {
            self::$pdo = new \PDO(
                $dsn,
                $login,
                $password,
                [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        }

        return self::$pdo;
    }
}
