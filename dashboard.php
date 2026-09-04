<?php

require_once "config.php";

require_login("login.php");

$employees = 0;
$payroll = 0;
$net_salary = 0;

try {

    $employees = (int)$conn->query(
        "SELECT COUNT(*) AS total FROM employees"
    )->fetch_assoc()["total"];

    $payroll = (int)$conn->query(
        "SELECT COUNT(*) AS total FROM payroll"
    )->fetch_assoc()["total"];

    $net_salary = (float)$conn->query(
        "SELECT COALESCE(SUM(net_salary),0) AS total
         FROM payroll"
    )->fetch_assoc()["total"];

} catch (mysqli_sql_exception $e) {

    error_log("Dashboard query error: " . $e->getMessage());

    flash_set("error", "Could not load dashboard statistics.");
}

$flash = flash_get();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <h2>Payroll System</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="employees/add.php">
        Register Employee
    </a>

    <a href="employees/list.php">
        Employees
    </a>

    <a href="payroll/add.php">
        Record Salary
    </a>

    <a href="payroll/list.php">
        Payroll
    </a>

    <a href="payroll/search.php">
        Search
    </a>

    <a href="payroll/report.php">
        Reports
    </a>

    <a href="logout.php">
        Logout
    </a>

</nav>

<div class="container">

    <h1>Welcome, <?= e($_SESSION["full_name"]) ?></h1>

    <?php if (!empty($flash["message"])): ?>
        <p class="<?= e($flash["type"] === "error" ? "error" : "message") ?>">
            <?= e($flash["message"]) ?>
        </p>
    <?php endif; ?>

    <div class="cards">

        <div class="card">
            <h3>Total Employees</h3>
            <h1><?= e($employees) ?></h1>
        </div>

        <div class="card">
            <h3>Payroll Records</h3>
            <h1><?= e($payroll) ?></h1>
        </div>

        <div class="card">
            <h3>Total Net Salary</h3>
            <h1>
                <?= number_format($net_salary, 2) ?>
            </h1>
            <p>RWF</p>
        </div>

    </div>

</div>

</body>
</html>
