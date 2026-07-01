<?php

/**
 * Database.php
 * Connexion centralisée à la base MySQL FitConnect via PDO.
 * Implémente un singleton pour éviter les connexions multiples.
 */
class Database
{
    private static ?PDO $instance = null;

    private const HOST    = '127.0.0.1';
    private const DBNAME  = 'fitconnect';
    private const CHARSET = 'utf8mb4';
    private const USER    = 'root';
    private const PASS    = '';

    private function __construct()
    {
        // Empêche l'instanciation directe
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::HOST,
                self::DBNAME,
                self::CHARSET
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    self::USER,
                    self::PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                // En production: logger l'erreur plutôt que l'afficher
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    // Empêche le clonage et la désérialisation du singleton
    private function __clone() {}
    public function __wakeup() {}
}
