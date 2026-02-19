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

namespace App;

use PDO;

class Database {
    public static function connect(): PDO {
        $host = getenv('MYSQLHOST') ?: '127.0.0.1';
        $port = getenv('MYSQLPORT') ?: '3306';
        $db   = getenv('MYSQLDATABASE') ?: 'fapi';
        $user = getenv('MYSQLUSER') ?: 'root';
        $pass = getenv('MYSQLPASSWORD') ?: '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}