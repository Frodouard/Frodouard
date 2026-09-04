<?php

session_start();

include "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Please enter email and password.";

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

                    session_regenerate_id(true);

                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["full_name"] = $user["full_name"];

                    header("Location: dashboard.php");
                    exit();
                }

                $message = "Incorrect password.";

            } else {

                $message = "User not found.";
            }

        } catch (mysqli_sql_exception $e) {

            $message = "Something went wrong. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Hotel Login</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="form-container">

    <h2>Hotel Staff Login</h2>

    <p class="error">
        <?php echo htmlspecialchars($message); ?>
    </p>

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

    <a href="register.php">
        Create Account
    </a>

</div>

</body>
</html>