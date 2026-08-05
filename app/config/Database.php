<?php
require_once __DIR__ . "/config.php";

class Database
{
    private static $instance = null;
    private mysqli $conn;

    private function __construct()
    {
        mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
        try {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $this->conn->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $e) {
            error_log("BookCycle DB: " . $e->getMessage());
            http_response_code(500);
            exit("No fue posible conectar con la base de datos.");
        }
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
    private function __clone() {}
}
