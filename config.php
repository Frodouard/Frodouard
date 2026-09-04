<?php

session_start();

require_once __DIR__ . "/functions.php";

$host = "localhost";
$user = "root";
$password = "";
$database = "employee_payroll";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    exit("Database connection failed. Please try again later.");
}

?>
