<?php

$id = $_POST["id"];
$year = $_POST["year"];
$sect = $_POST["section"];

require "../include/config.php";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() );
}

$sql = "INSERT INTO tbl_class (id, year, section) VALUES ('$id', '$year', '$sect')";

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
