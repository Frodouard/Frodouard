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

    $sql = "SELECT
                consultations.*,
                patients.patient_id AS patient_number,
                patients.first_name,
                patients.last_name
            FROM consultations
            INNER JOIN patients
            ON consultations.patient_id = patients.id
            ORDER BY consultations.visit_date DESC";

    $result = $conn->query($sql);

} catch (mysqli_sql_exception $e) {

    $error = "Could not load consultations. Please try again later.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Consultations</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="table-container">

    <h2>Consultation Records</h2>

    <a href="consultant_add.php" class="btn">
        Add Consultation
    </a>

    <?php if ($error != ""): ?>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <?php if ($result !== null): ?>

    <table>

        <tr>

            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Diagnosis</th>

        </tr>

        <?php if ($result->num_rows > 0): ?>

            <?php while ($row = $result->fetch_assoc()): ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($row["patient_number"]); ?>
                </td>

                <td>

                    <?php

                    echo htmlspecialchars(
                        $row["first_name"] .
                        " " .
                        $row["last_name"]
                    );

                    ?>

                </td>

                <td>
                    <?php echo htmlspecialchars($row["doctor_name"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["visit_date"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["diagnosis"]); ?>
                </td>

            </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="5">No consultations recorded yet.</td>
            </tr>

        <?php endif; ?>

    </table>

    <?php endif; ?>

</div>

</body>
</html>
