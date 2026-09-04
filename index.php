<?php
$message = "";
$messageType = "";

// ==========================
// PROCESS FORM
// ==========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = trim($_POST["fullName"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $program = trim($_POST["program"]);
    $studentMessage = trim($_POST["message"]);

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($phone) || empty($gender) || empty($program)) {

        header("Location: index.php?status=error");
        exit();

    } else {

        // Build record
        $record = "==================================\n";
        $record .= "Full Name : $fullName\n";
        $record .= "Email     : $email\n";
        $record .= "Phone     : $phone\n";
        $record .= "Gender    : $gender\n";
        $record .= "Program   : $program\n";
        $record .= "Message   : " . ($studentMessage == "" ? "N/A" : $studentMessage) . "\n";
        $record .= "Date      : " . date("Y-m-d H:i:s") . "\n";
        $record .= "====================================\n";

        // Save to file
        $file = fopen("records.txt", "a");

        if ($file) {

            fwrite($file, $record);
            fclose($file);

            // Redirect after saving
            header("Location: index.php?status=success");
            exit();

        } else {

            header("Location: index.php?status=fileerror");
            exit();
        }
    }
}

// ==========================
// READ RECORDS
// ==========================
$records = "";

if (file_exists("records.txt")) {
    $records = file_get_contents("records.txt");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Registration System</title>

<style>

body{
    font-family:Arial, Helvetica, sans-serif;
    background:#f2f2f2;
}

.container{
    width:800px;
    margin:30px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px gray;
}

h1{
    text-align:center;
    color:green;
}

.form-group{
    margin-bottom:15px;
}

label{
    font-weight:bold;
}

input[type=text],
input[type=email],
input[type=tel],
select,
textarea{

    width:100%;
    padding:10px;
    margin-top:5px;
    border:1px solid #ccc;
    border-radius:5px;
}

textarea{
    height:100px;
}

button{

    width:100%;
    padding:12px;
    background:green;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:darkgreen;
}

.success{

    background:#d4edda;
    color:#155724;
    padding:12px;
    margin-bottom:20px;
    border-radius:5px;
}

.error{

    background:#f8d7da;
    color:#721c24;
    padding:12px;
    margin-bottom:20px;
    border-radius:5px;
}

pre{

    background:#eee;
    padding:15px;
    border-radius:5px;
    white-space:pre-wrap;
}

.radio-group{

    margin-top:8px;
}

</style>

</head>

<body>

<div class="container">

<h1>Student Registration System</h1>

<?php

if(isset($_GET["status"])){

    if($_GET["status"]=="success"){
        echo "<div class='success'>Student registered successfully!</div>";
    }

    if($_GET["status"]=="error"){
        echo "<div class='error'>Please fill in all required fields.</div>";
    }

    if($_GET["status"]=="fileerror"){
        echo "<div class='error'>Unable to open records.txt.</div>";
    }

}

?>

<form method="POST" action="">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="fullName">
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email">
</div>

<div class="form-group">
<label>Phone Number</label>
<input type="tel" name="phone">
</div>

<div class="form-group">

<label>Gender</label>

<div class="radio-group">

<input type="radio" name="gender" value="Male"> Male

<input type="radio" name="gender" value="Female"> Female

<input type="radio" name="gender" value="Other"> Other

</div>

</div>

<div class="form-group">

<label>Program</label>

<select name="program">

<option value="">Select Program</option>

<option>Software Engineering</option>

<option>Computer Science</option>

<option>Information Systems</option>

<option>Networking</option>

<option>Big Data</option>

</select>

</div>

<div class="form-group">

<label>Message</label>

<textarea name="message"></textarea>

</div>

<button type="submit">Submit Registration</button>

</form>

<hr>

<h2>Registered Students</h2>

<?php

if(!empty($records)){

    echo "<pre>";
    echo htmlspecialchars($records);
    echo "</pre>";

}else{

    echo "<p>No students registered yet.</p>";

}

?>

</div>

</body>
</html>