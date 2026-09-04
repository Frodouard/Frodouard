<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$result = null;
$message = "";
$is_error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $search = trim($_POST["search"] ?? "");

    if ($search === "") {

        $message = "Please enter a patient ID or phone number.";
        $is_error = true;

    } else {

        try {

            $sql = "SELECT * FROM patients
                    WHERE patient_id = ?
                    OR phone = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ss",
                $search,
                $search
            );

            $stmt->execute();

            $result = $stmt->get_result();

        } catch (mysqli_sql_exception $e) {

            $message = "Search failed due to a database error. Please try again.";
            $is_error = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Search Patient</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="form-container">

    <h2>Search Patient</h2>

    <?php if ($message != ""): ?>

        <p class="<?php echo $is_error ? 'error' : 'message'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <input
            type="text"
            name="search"
            placeholder="Patient ID or Phone Number"
            required
        >

        <button type="submit">
            Search
        </button>

    </form>

    <?php if ($result !== null): ?>

        <?php if ($result->num_rows > 0): ?>

            <?php while ($patient = $result->fetch_assoc()): ?>

                <div class="patient-result">

                    <h3>
                        <?php
                        echo htmlspecialchars(
                            $patient["first_name"] .
                            " " .
                            $patient["last_name"]
                        );
                        ?>
                    </h3>

                    <p>
                        Patient ID:
                        <?php echo htmlspecialchars($patient["patient_id"]); ?>
                    </p>

                    <p>
                        Phone:
                        <?php echo htmlspecialchars($patient["phone"]); ?>
                    </p>

                    <p>
                        Gender:
                        <?php echo htmlspecialchars($patient["gender"]); ?>
                    </p>

                    <a href="patient_edit.php?id=<?php echo $patient['id']; ?>">
                        Edit
                    </a>

                    <a href="patient_report.php?id=<?php echo $patient['id']; ?>">
                        Medical Report
                    </a>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p class="error">
                Patient not found.
            </p>

        <?php endif; ?>

    <?php endif; ?>

    <br>

    <a href="patient_list.php">Back to Patients</a>

</div>

</body>
</html>
