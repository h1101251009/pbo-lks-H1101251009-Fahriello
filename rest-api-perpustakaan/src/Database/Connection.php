<?php

namespace App\Database;

use App\Config\Env;
use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $host = Env::get('DB_HOST', 'localhost');
            $port = Env::get('DB_PORT', '3306');
            $db   = Env::get('DB_DATABASE');
            $user = Env::get('DB_USERNAME');
            $pass = Env::get('DB_PASSWORD', '');

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException('Koneksi gagal: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
