<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {

    header("Location: patient_list.php?msg=error");

    exit();
}

try {

    $sql = "DELETE FROM patients WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

    $stmt->execute();

    header("Location: patient_list.php?msg=deleted");

} catch (mysqli_sql_exception $e) {

    header("Location: patient_list.php?msg=error");
}

exit();

?>
