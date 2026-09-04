<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Patient Record System</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="home-container">

    <h1>Hospital Patient Record Management System</h1>

    <p>
        Manage patients, consultations and medical reports efficiently.
    </p>

    <div>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    </div>

</div>

</body>
</html>