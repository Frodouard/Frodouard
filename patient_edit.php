<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

$patient = null;
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

        if (!$patient) {

            $error = "Patient not found.";
        }

    } catch (mysqli_sql_exception $e) {

        $error = "Could not load patient record. Please try again.";
    }
}

if ($patient && $_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $gender = $_POST["gender"] ?? "";
    $date_of_birth = $_POST["date_of_birth"] ?? "";
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $emergency_contact = trim($_POST["emergency_contact"] ?? "");

    $dob_time = strtotime($date_of_birth);

    if ($first_name === "" || $last_name === "" || $gender === "" ||
        $date_of_birth === "" || $phone === "" || $address === "") {

        $error = "Please fill in all required fields.";

    } elseif ($dob_time === false) {

        $error = "Please enter a valid date of birth.";

    } elseif ($dob_time > time()) {

        $error = "Date of birth cannot be in the future.";

    } else {

        try {

            $sql = "UPDATE patients
                    SET first_name = ?,
                        last_name = ?,
                        gender = ?,
                        date_of_birth = ?,
                        phone = ?,
                        address = ?,
                        emergency_contact = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssssssi",
                $first_name,
                $last_name,
                $gender,
                $date_of_birth,
                $phone,
                $address,
                $emergency_contact,
                $id
            );

            $stmt->execute();

            header("Location: patient_list.php?msg=saved");

            exit();

        } catch (mysqli_sql_exception $e) {

            $error = "Failed to update patient due to a database error.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Update Patient</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="form-container">

    <h2>Update Patient</h2>

    <?php if ($error != ""): ?>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <?php if ($patient === null): ?>

        <p>
            <a href="patient_list.php">Back to Patients</a>
        </p>

    <?php else: ?>

    <form method="POST">

        <label>Patient ID</label>

        <input
            type="text"
            value="<?php echo htmlspecialchars($patient['patient_id']); ?>"
            disabled
        >

        <label>First Name</label>

        <input
            type="text"
            name="first_name"
            value="<?php echo htmlspecialchars($patient['first_name']); ?>"
            required
        >

        <label>Last Name</label>

        <input
            type="text"
            name="last_name"
            value="<?php echo htmlspecialchars($patient['last_name']); ?>"
            required
        >

        <label>Gender</label>

        <select name="gender">

            <option value="Male"
                <?php if ($patient["gender"] == "Male") echo "selected"; ?>>
                Male
            </option>

            <option value="Female"
                <?php if ($patient["gender"] == "Female") echo "selected"; ?>>
                Female
            </option>

        </select>

        <label>Date of Birth</label>

        <input
            type="date"
            name="date_of_birth"
            value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>"
            required
        >

        <label>Phone</label>

        <input
            type="text"
            name="phone"
            value="<?php echo htmlspecialchars($patient['phone']); ?>"
            required
        >

        <label>Address</label>

        <input
            type="text"
            name="address"
            value="<?php echo htmlspecialchars($patient['address']); ?>"
            required
        >

        <label>Emergency Contact</label>

        <input
            type="text"
            name="emergency_contact"
            value="<?php echo htmlspecialchars($patient['emergency_contact']); ?>"
        >

        <button type="submit">
            Update Patient
        </button>

    </form>

    <?php endif; ?>

</div>

</body>
</html>
