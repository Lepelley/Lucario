<?php

namespace App\Database;

class ConnectionMySQL
{
    private static ?\PDO $pdo = null;

    /**
     * @param array<string,array<string,string>> $credentials
     *
     * @return \PDO
     *
     * @throws DatabaseException
     */
    public static function get(array $credentials): \PDO
    {
        $dsn = isset($credentials['DSN']) ? $credentials['DSN'] : '';
        $login = isset($credentials['LOGIN']) ? $credentials['LOGIN'] : '';
        $password = isset($credentials['PASSWORD']) ? $credentials['PASSWORD'] : '';

        if ('' === $dsn || '' === $login) {
            throw new DatabaseException('Bad credentials');
        }

        if (null === self::$pdo) {
            try {
                self::$pdo = new \PDO(
                    $dsn,
                    $login,
                    $password,
                    [
                        \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    ]
                );
            } catch (\Exception $error) {
                throw new DatabaseException(
                    \sprintf('Échec lors de la connexion à la base de données : %s, ', $error->getMessage())
                );
            }
        }

        return self::$pdo;
    }
}
