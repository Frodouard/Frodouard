<?php

require_once "config.php";

$message = "";
$message_type = "";
$full_name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    csrf_verify();

    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($full_name === "" || $email === "" || $password === "") {

        $message = "All fields are required.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 8) {

        $message = "Password must be at least 8 characters long.";
        $message_type = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {

        try {

            $stmt = $conn->prepare(
                "SELECT id FROM users WHERE email = ?"
            );

            $stmt->bind_param("s", $email);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {

                $message = "An account with this email already exists.";
                $message_type = "error";

            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare(
                    "INSERT INTO users (full_name, email, password)
                     VALUES (?,?,?)"
                );

                $stmt->bind_param("sss", $full_name, $email, $hashed_password);

                if ($stmt->execute()) {

                    $message = "Registration successful. You can now log in.";
                    $message_type = "success";

                } else {

                    $message = "Registration failed. Please try again.";
                    $message_type = "error";
                }
            }

        } catch (mysqli_sql_exception $e) {

            error_log("Registration error: " . $e->getMessage());

            $message = "Registration failed. Please try again later.";
            $message_type = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="form-container">

    <h2>Create Account</h2>

    <?php if ($message): ?>
        <p class="<?= e($message_type) ?>">
            <?= e($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <?= csrf_field() ?>

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= e($full_name) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= e($email) ?>" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">
            Register
        </button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>
