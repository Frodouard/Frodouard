<?php

require_once "config.php";

$message = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    csrf_verify();

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Email and password are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        try {

            $stmt = $conn->prepare(
                "SELECT * FROM users WHERE email = ?"
            );

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $user = $stmt->get_result()->fetch_assoc();

            if ($user && password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["full_name"] = $user["full_name"];

                redirect("dashboard.php");

            } else {

                $message = "Invalid email or password.";
            }

        } catch (mysqli_sql_exception $e) {

            error_log("Login error: " . $e->getMessage());

            $message = "Login failed. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="form-container">

    <h2>Employee Payroll System</h2>

    <h3>Login</h3>

    <?php if ($message): ?>
        <p class="error">
            <?= e($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <?= csrf_field() ?>

        <label>Email</label>

        <input
            type="email"
            name="email"
            value="<?= e($email) ?>"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>
