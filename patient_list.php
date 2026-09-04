<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$result = null;
$error = "";

try {

    $result = $conn->query(
        "SELECT * FROM patients ORDER BY id DESC"
    );

} catch (mysqli_sql_exception $e) {

    $error = "Could not load patients. Please try again later.";
}

$notice = "";

if (isset($_GET["msg"])) {

    if ($_GET["msg"] == "deleted") {

        $notice = "Patient deleted successfully.";

    } elseif ($_GET["msg"] == "saved") {

        $notice = "Patient updated successfully.";

    } elseif ($_GET["msg"] == "error") {

        $notice = "Could not delete patient. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Patients</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <h2>Hospital System</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="patient_add.php">Add Patient</a>

    <a href="logout.php">Logout</a>

</nav>

<div class="table-container">

    <h2>Registered Patients</h2>

    <a href="patient_search.php" class="btn">
        Search Patient
    </a>

    <?php if ($notice != ""): ?>

        <p class="message">
            <?php echo htmlspecialchars($notice); ?>
        </p>

    <?php endif; ?>

    <?php if ($error != ""): ?>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <?php if ($result !== null): ?>

    <table>

        <tr>

            <th>Patient ID</th>

            <th>Name</th>

            <th>Gender</th>

            <th>Phone</th>

            <th>Address</th>

            <th>Actions</th>

        </tr>

        <?php if ($result->num_rows > 0): ?>

            <?php while ($patient = $result->fetch_assoc()): ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($patient["patient_id"]); ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $patient["first_name"] . " " .
                        $patient["last_name"]
                    );
                    ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($patient["gender"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($patient["phone"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($patient["address"]); ?>
                </td>

                <td>

                    <a href="patient_edit.php?id=<?php echo $patient['id']; ?>">
                        Edit
                    </a>

                    <a href="patient_report.php?id=<?php echo $patient['id']; ?>">
                        Report
                    </a>

                    <a href="delete.php?id=<?php echo $patient['id']; ?>"
                       onclick="return confirm('Delete this patient?');">
                        Delete
                    </a>

                </td>

            </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">No patients registered yet.</td>
            </tr>

        <?php endif; ?>

    </table>

    <?php endif; ?>

</div>

</body>
</html>
