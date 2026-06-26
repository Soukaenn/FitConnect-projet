<?php

class Database
{
    private static ?PDO $instance = null;

    private static string $host     = 'localhost';
    private static string $dbname   = 'fitconnect';
    private static string $charset  = 'utf8mb4';
    private static string $user     = 'root';
    private static string $password = '';

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::$host,
                self::$dbname,
                self::$charset
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, self::$user, self::$password, $options);
            } catch (PDOException $e) {
                // Ne jamais exposer les détails en production
                error_log('Erreur de connexion : ' . $e->getMessage());
                throw new RuntimeException('Impossible de se connecter à la base de données.');
            }
        }

        return self::$instance;
    }
}