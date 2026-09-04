<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$total_patients = 0;
$total_consultations = 0;
$error = "";

try {

    $patient_result = $conn->query(
        "SELECT COUNT(*) AS total FROM patients"
    );

    $patient_data = $patient_result->fetch_assoc();

    $total_patients = $patient_data["total"];

    $consultation_result = $conn->query(
        "SELECT COUNT(*) AS total FROM consultations"
    );

    $consultation_data = $consultation_result->fetch_assoc();

    $total_consultations = $consultation_data["total"];

} catch (mysqli_sql_exception $e) {

    $error = "Could not load dashboard data. Please try again later.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <h2>Hospital System</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="patient_add.php">Register Patient</a>
    <a href="patient_list.php">Patients</a>
    <a href="consultant_list.php">Consultations</a>
    <a href="logout.php">Logout</a>

</nav>

<div class="dashboard">

    <h1>Welcome,
        <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
    </h1>

    <?php if ($error != ""): ?>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <div class="cards">

        <div class="card">

            <h3>Total Patients</h3>

            <p>
                <?php echo htmlspecialchars($total_patients); ?>
            </p>

        </div>

        <div class="card">

            <h3>Total Consultations</h3>

            <p>
                <?php echo htmlspecialchars($total_consultations); ?>
            </p>

        </div>

    </div>

</div>

</body>
</html>
