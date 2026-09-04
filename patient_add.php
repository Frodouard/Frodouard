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

    $patient_id = trim($_POST["patient_id"] ?? "");
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $gender = $_POST["gender"] ?? "";
    $date_of_birth = $_POST["date_of_birth"] ?? "";
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $emergency_contact = trim($_POST["emergency_contact"] ?? "");

    $dob_time = strtotime($date_of_birth);

    if ($patient_id === "" || $first_name === "" || $last_name === "" ||
        $gender === "" || $date_of_birth === "" || $phone === "" ||
        $address === "") {

        $message = "Please fill in all required fields.";
        $is_error = true;

    } elseif ($dob_time === false) {

        $message = "Please enter a valid date of birth.";
        $is_error = true;

    } elseif ($dob_time > time()) {

        $message = "Date of birth cannot be in the future.";
        $is_error = true;

    } else {

        try {

            $sql = "INSERT INTO patients
                    (patient_id, first_name, last_name, gender,
                     date_of_birth, phone, address, emergency_contact)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssssss",
                $patient_id,
                $first_name,
                $last_name,
                $gender,
                $date_of_birth,
                $phone,
                $address,
                $emergency_contact
            );

            $stmt->execute();

            $message = "Patient registered successfully.";

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {

                $message = "Patient ID already exists. Please use a different ID.";

            } else {

                $message = "Failed to register patient due to a database error.";
            }

            $is_error = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register Patient</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav>

    <h2>Hospital System</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="patient_list.php">Patients</a>
    <a href="logout.php">Logout</a>

</nav>

<div class="form-container">

    <h2>Patient Registration</h2>

    <?php if ($message != ""): ?>

        <p class="<?php echo $is_error ? 'error' : 'message'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <label>Patient ID</label>

        <input
            type="text"
            name="patient_id"
            placeholder="Example: P-0001"
            required
        >

        <label>First Name</label>

        <input
            type="text"
            name="first_name"
            required
        >

        <label>Last Name</label>

        <input
            type="text"
            name="last_name"
            required
        >

        <label>Gender</label>

        <select name="gender" required>

            <option value="">Select Gender</option>

            <option value="Male">Male</option>

            <option value="Female">Female</option>

        </select>

        <label>Date of Birth</label>

        <input
            type="date"
            name="date_of_birth"
            required
        >

        <label>Phone Number</label>

        <input
            type="text"
            name="phone"
            required
        >

        <label>Address</label>

        <input
            type="text"
            name="address"
            required
        >

        <label>Emergency Contact</label>

        <input
            type="text"
            name="emergency_contact"
        >

        <button type="submit">
            Register Patient
        </button>

    </form>

</div>

</body>
</html>
