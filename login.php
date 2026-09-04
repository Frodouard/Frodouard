<?php

session_start();

require_once "config.php";

$message = "";
$is_error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Please enter your email and password.";
        $is_error = true;

    } else {

        try {

            $sql = "SELECT * FROM users WHERE email = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("s", $email);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $user = $result->fetch_assoc();

                if (password_verify($password, $user["password"])) {

                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["full_name"] = $user["full_name"];
                    $_SESSION["role"] = $user["role"];

                    header("Location: dashboard.php");
                    exit();

                } else {

                    $message = "Incorrect password.";
                    $is_error = true;
                }

            } else {

                $message = "User not found.";
                $is_error = true;
            }

        } catch (mysqli_sql_exception $e) {

            $message = "Login failed due to a database error. Please try again.";
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

    <title>Login</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="form-container">

    <h2>Staff Login</h2>

    <?php if ($message != ""): ?>

        <p class="<?php echo $is_error ? 'error' : 'message'; ?>">
            <?php echo htmlspecialchars($message); ?>
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

        <button type="submit">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>
