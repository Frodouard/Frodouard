<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

$patient = null;
$consultations = null;
$error = "";

if ($id <= 0) {

    $error = "Invalid patient ID.";

} else {

    try {

        $sql = "SELECT * FROM patients WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $patient = $stmt->get_result()->fetch_assoc();

        if ($patient) {

            $sql = "SELECT *
                    FROM consultations
                    WHERE patient_id = ?
                    ORDER BY visit_date DESC";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $consultations = $stmt->get_result();

        } else {

            $error = "Patient not found.";
        }

    } catch (mysqli_sql_exception $e) {

        $error = "Could not load the medical report. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Medical Report</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="report">

    <?php if ($error != ""): ?>

        <h1>HOSPITAL PATIENT MEDICAL REPORT</h1>

        <hr>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

        <button
            onclick="window.history.back()"
            class="no-print"
        >
            Back
        </button>

    <?php else: ?>

    <h1>HOSPITAL PATIENT MEDICAL REPORT</h1>

    <hr>

    <h2>Patient Information</h2>

    <p>
        <strong>Patient ID:</strong>
        <?php echo htmlspecialchars($patient["patient_id"]); ?>
    </p>

    <p>
        <strong>Name:</strong>

        <?php

        echo htmlspecialchars(
            $patient["first_name"] .
            " " .
            $patient["last_name"]
        );

        ?>

    </p>

    <p>
        <strong>Gender:</strong>
        <?php echo htmlspecialchars($patient["gender"]); ?>
    </p>

    <p>
        <strong>Date of Birth:</strong>
        <?php echo htmlspecialchars($patient["date_of_birth"]); ?>
    </p>

    <p>
        <strong>Phone:</strong>
        <?php echo htmlspecialchars($patient["phone"]); ?>
    </p>

    <p>
        <strong>Address:</strong>
        <?php echo htmlspecialchars($patient["address"]); ?>
    </p>

    <hr>

    <h2>Consultation History</h2>

    <?php if ($consultations->num_rows > 0): ?>

        <?php while ($consultation = $consultations->fetch_assoc()): ?>

            <div class="consultation">

                <p>
                    <strong>Doctor:</strong>
                    <?php
                    echo htmlspecialchars(
                        $consultation["doctor_name"]
                    );
                    ?>
                </p>

                <p>
                    <strong>Visit Date:</strong>
                    <?php
                    echo htmlspecialchars(
                        $consultation["visit_date"]
                    );
                    ?>
                </p>

                <p>
                    <strong>Symptoms:</strong>
                    <?php
                    echo htmlspecialchars(
                        $consultation["symptoms"]
                    );
                    ?>
                </p>

                <p>
                    <strong>Diagnosis:</strong>
                    <?php
                    echo htmlspecialchars(
                        $consultation["diagnosis"]
                    );
                    ?>
                </p>

                <p>
                    <strong>Treatment:</strong>
                    <?php
                    echo htmlspecialchars(
                        $consultation["treatment"]
                    );
                    ?>
                </p>

                <p>
                    <strong>Prescription:</strong>
                    <?php
                    echo htmlspecialchars(
                        $consultation["prescription"]
                    );
                    ?>
                </p>

                <hr>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>No consultation records available.</p>

    <?php endif; ?>

    <button onclick="window.print()">
        Print Medical Report
    </button>

    <button
        onclick="window.history.back()"
        class="no-print"
    >
        Back
    </button>

    <?php endif; ?>

</div>

</body>
</html>
