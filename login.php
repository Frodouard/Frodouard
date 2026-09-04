<?php

session_start();

require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT *
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param(
        "s",
        $email
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (
            password_verify(
                $password,
                $user["password"]
            )
        ) {

            $_SESSION["user_id"] =
                $user["id"];

            $_SESSION["full_name"] =
                $user["full_name"];

            header(
                "Location: dashboard.php"
            );

            exit;

        } else {

            $message =
                "Incorrect password.";

        }

    } else {

        $message =
            "Account not found.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Staff Login</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="auth-container">

    <h1>Vehicle Service System</h1>

    <h2>Staff Login</h2>

    <?php if ($message): ?>

        <p class="error">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
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
        <a href="register.php">
            Register
        </a>
    </p>

</div>

</body>

</html>