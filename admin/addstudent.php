<?php

$user = $_POST["user"];
$pass = $_POST["pass"];
$name = $_POST["name"];
$surname = $_POST["surname"];
$passCheck = $_POST["passCheck"];
$class = $_POST["class"];

if ($pass != $passCheck)
{
    return;
}

require "../include/config.php";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() );
}

$sql = "INSERT INTO tbl_student (user, pass, name, surname, class) VALUES ('$user', '$pass', '$name', '$surname', '$class')";

$result = mysqli_query($conn, $sql);

if ($result) 
{
    echo "New record created successfully";
    header("Location: students.php");
} 
else 
{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
