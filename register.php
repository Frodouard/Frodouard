<?php

include "config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($full_name === "" || $email === "" || $password === "") {

        $message = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO users
                    (full_name, email, password)
                    VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {

                $message = "Registration successful. You can now log in.";
                $message_type = "success";

            }

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {

                $message = "Registration failed. Email already exists.";

            } else {

                $message = "Something went wrong. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Staff Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="form-container">

    <h2>Hotel Staff Registration</h2>

    <p class="<?php echo $message_type === "success" ? "success" : "error"; ?>">
        <?php echo htmlspecialchars($message); ?>
    </p>

    <form method="POST">

        <label>Full Name</label>

        <input
            type="text"
            name="full_name"
            required
        >

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
            Register
        </button>

    </form>

    <p>
        Already registered?
        <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>