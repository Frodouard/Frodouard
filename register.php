<?php

require_once "config.php";

$message = "";
$is_error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($full_name === "" || $email === "" || $password === "") {

        $message = "Please fill in all fields.";
        $is_error = true;

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $is_error = true;

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters long.";
        $is_error = true;

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO users (full_name, email, password)
                    VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sss",
                $full_name,
                $email,
                $hashed_password
            );

            $stmt->execute();

            $message = "Registration successful. You can now login.";

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {

                $message = "Email already exists. Please login instead.";

            } else {

                $message = "Registration failed due to a database error. Please try again.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Staff Registration</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="form-container">

    <h2>Staff Registration</h2>

    <?php if ($message != ""): ?>
        <p class="<?php echo $is_error ? 'error' : 'message'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateRegistration()">

        <label>Full Name</label>
        <input
            type="text"
            name="full_name"
            id="full_name"
            required
        >

        <label>Email</label>
        <input
            type="email"
            name="email"
            id="email"
            required
        >

        <label>Password</label>
        <input
            type="password"
            name="password"
            id="password"
            required
        >

        <button type="submit">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

<script src="js/script.js"></script>

</body>
</html>
