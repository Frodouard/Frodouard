<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$message = "";
$is_error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = $_POST["patient_id"] ?? "";
    $doctor_name = trim($_POST["doctor_name"] ?? "");
    $visit_date = $_POST["visit_date"] ?? "";
    $symptoms = trim($_POST["symptoms"] ?? "");
    $diagnosis = trim($_POST["diagnosis"] ?? "");
    $treatment = trim($_POST["treatment"] ?? "");
    $prescription = trim($_POST["prescription"] ?? "");
    $notes = trim($_POST["notes"] ?? "");

    $visit_time = strtotime($visit_date);

    if ($patient_id === "" || $doctor_name === "" || $visit_date === "" ||
        $symptoms === "" || $diagnosis === "") {

        $message = "Please fill in all required fields.";
        $is_error = true;

    } elseif (!ctype_digit((string)$patient_id)) {

        $message = "Please select a valid patient.";
        $is_error = true;

    } elseif ($visit_time === false) {

        $message = "Please enter a valid visit date.";
        $is_error = true;

    } else {

        try {

            $sql = "INSERT INTO consultations
                    (patient_id, doctor_name, visit_date,
                     symptoms, diagnosis, treatment,
                     prescription, notes)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "isssssss",
                $patient_id,
                $doctor_name,
                $visit_date,
                $symptoms,
                $diagnosis,
                $treatment,
                $prescription,
                $notes
            );

            $stmt->execute();

            $message = "Consultation recorded successfully.";

        } catch (mysqli_sql_exception $e) {

            $message = "Failed to record consultation due to a database error.";
            $is_error = true;
        }
    }
}

$patients = null;
$load_error = "";

try {

    $patients = $conn->query(
        "SELECT id, patient_id, first_name, last_name
         FROM patients
         ORDER BY first_name"
    );

} catch (mysqli_sql_exception $e) {

    $load_error = "Could not load patients. Please try again later.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Consultation</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="form-container">

    <h2>Record Consultation</h2>

    <?php if ($message != ""): ?>

        <p class="<?php echo $is_error ? 'error' : 'message'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <?php if ($load_error != ""): ?>

        <p class="error">
            <?php echo htmlspecialchars($load_error); ?>
        </p>

        <a href="dashboard.php">Back to Dashboard</a>

    <?php elseif ($patients !== null && $patients->num_rows == 0): ?>

        <p class="error">
            No patients registered yet. Please register a patient first.
        </p>

        <a href="patient_add.php">Register Patient</a>

    <?php else: ?>

    <form method="POST">

        <label>Patient</label>

        <select name="patient_id" required>

            <option value="">
                Select Patient
            </option>

            <?php while ($patient = $patients->fetch_assoc()): ?>

                <option value="<?php echo $patient["id"]; ?>">

                    <?php
                    echo htmlspecialchars(
                        $patient["patient_id"] .
                        " - " .
                        $patient["first_name"] .
                        " " .
                        $patient["last_name"]
                    );
                    ?>

                </option>

            <?php endwhile; ?>

        </select>

        <label>Doctor Name</label>

        <input
            type="text"
            name="doctor_name"
            required
        >

        <label>Visit Date</label>

        <input
            type="date"
            name="visit_date"
            required
        >

        <label>Symptoms</label>

        <textarea
            name="symptoms"
            required
        ></textarea>

        <label>Diagnosis</label>

        <textarea
            name="diagnosis"
            required
        ></textarea>

        <label>Treatment</label>

        <textarea
            name="treatment"
        ></textarea>

        <label>Prescription</label>

        <textarea
            name="prescription"
        ></textarea>

        <label>Notes</label>

        <textarea
            name="notes"
        ></textarea>

        <button type="submit">
            Save Consultation
        </button>

    </form>

    <?php endif; ?>

</div>

</body>
</html>
