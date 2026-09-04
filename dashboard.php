<?php

session_start();

include "config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$customers = 0;
$reservations = 0;
$rooms = 0;

try {

    $customers = (int) $conn->query(
        "SELECT COUNT(*) AS total
         FROM customers"
    )->fetch_assoc()["total"];

    $reservations = (int) $conn->query(
        "SELECT COUNT(*) AS total
         FROM reservations"
    )->fetch_assoc()["total"];

    $rooms = (int) $conn->query(
        "SELECT COUNT(*) AS total
         FROM rooms"
    )->fetch_assoc()["total"];

} catch (mysqli_sql_exception $e) {

    $error = "Could not load dashboard statistics.";
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Hotel Dashboard</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<nav>

    <h2>Hotel Reservation System</h2>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="customers/add.php">
        Customers
    </a>

    <a href="reservations/add.php">
        Reservation
    </a>

    <a href="reservations/list.php">
        Reservations
    </a>

    <a href="logout.php">
        Logout
    </a>

</nav>

<div class="dashboard">

    <h1>
        Welcome,
        <?php echo htmlspecialchars(
            $_SESSION["full_name"]
        ); ?>
    </h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <div class="cards">

        <div class="card">

            <h3>Customers</h3>

            <p>
                <?php echo $customers; ?>
            </p>

        </div>

        <div class="card">

            <h3>Rooms</h3>

            <p>
                <?php echo $rooms; ?>
            </p>

        </div>

        <div class="card">

            <h3>Reservations</h3>

            <p>
                <?php echo $reservations; ?>
            </p>

        </div>

    </div>

</div>

</body>
</html>