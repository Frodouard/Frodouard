<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}

$total_customers =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM customers"
    )->fetch_assoc()["total"];

$total_vehicles =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM vehicles"
    )->fetch_assoc()["total"];

$total_services =
    $conn->query(
        "SELECT COUNT(*) AS total
         FROM services"
    )->fetch_assoc()["total"];

$total_revenue =
    $conn->query(
        "SELECT COALESCE(
            SUM(service_charge),0
         ) AS total
         FROM services"
    )->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<nav>

    <div class="logo">
        Vehicle Service System
    </div>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="customers/add.php">
        Add Customer
    </a>

    <a href="customers/list.php">
        Customers
    </a>

    <a href="vehicles/add.php">
        Add Vehicle
    </a>

    <a href="vehicles/list.php">
        Vehicles
    </a>

    <a href="services/add.php">
        Add Service
    </a>

    <a href="services/list.php">
        Services
    </a>

    <a href="services/search.php">
        Search
    </a>

    <a href="logout.php">
        Logout
    </a>

</nav>

<div class="container">

    <h1>
        Welcome,
        <?= htmlspecialchars(
            $_SESSION["full_name"]
        ) ?>
    </h1>

    <div class="cards">

        <div class="card">

            <h3>
                Customers
            </h3>

            <h1>
                <?= $total_customers ?>
            </h1>

        </div>

        <div class="card">

            <h3>
                Vehicles
            </h3>

            <h1>
                <?= $total_vehicles ?>
            </h1>

        </div>

        <div class="card">

            <h3>
                Services
            </h3>

            <h1>
                <?= $total_services ?>
            </h1>

        </div>

        <div class="card">

            <h3>
                Total Revenue
            </h3>

            <h1>
                <?= number_format(
                    $total_revenue,
                    2
                ) ?>
            </h1>

            <p>RWF</p>

        </div>

    </div>

</div>

</body>

</html>