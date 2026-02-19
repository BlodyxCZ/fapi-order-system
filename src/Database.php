<?php

namespace App;

use PDO;

/**
 * Simple database connector helper.
 *
 * This class centralizes PDO creation so other parts of the app can
 * obtain a configured PDO instance with consistent error/fetch modes.
 *
 * In a real app you would typically read credentials from environment
 * variables or a configuration file instead of hard-coding them.
 */
class Database
{
    public static function connect(): PDO
    {
        // NOTE: values are simple defaults for local/dev environment
        $host = getenv("MARIADB_HOST");
        $user = getenv("MARIADB_USER");
        $pass = getenv("MARIADB_PASSWORD");
        $db   = getenv("MARIADB_DATABASE");
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        // Create PDO with sensible defaults: exceptions on error and associative fetch
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}