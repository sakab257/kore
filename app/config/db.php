<?php
/**
 * Database Configuration & Connection (Singleton Pattern)
 *
 * Provides a single PDO instance throughout the application.
 * Secure, efficient, and follows best practices.
 */

class Database
{
    // Database credentials
    private const HOST = 'localhost';
    private const DB_NAME = 'kore_shop';
    private const USERNAME = 'root';
    private const PASSWORD = 'root'; // Change in production

    // PDO instance (Singleton)
    private static ?Database $instance = null;
    private PDO $connection;

    /**
     * Private constructor to prevent direct instantiation
     * Establishes the database connection with optimal settings
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,    // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Fetch as associative arrays
                PDO::ATTR_EMULATE_PREPARES   => false,                     // Use real prepared statements
                PDO::ATTR_PERSISTENT         => false,                     // No persistent connections
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"        // Proper charset
            ];

            $this->connection = new PDO($dsn, self::USERNAME, self::PASSWORD, $options);

        } catch (PDOException $e) {
            // In production, log this error instead of displaying it
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}

    /**
     * Prevent unserializing of the instance
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * Get the singleton instance
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get the PDO connection
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Rollback a transaction
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }
}
