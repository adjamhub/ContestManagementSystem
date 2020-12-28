<?php

require('include/config.php');

// inizializzazione della sessione
session_start();

// set time-out period (in seconds)
$inactive = 600;
// check to see if $_SESSION["timeout"] is set
if (isset($_SESSION["timeout"])) 
{
    // calculate the session's "time to live"
    $sessionTTL = time() - $_SESSION["timeout"];
    if ($sessionTTL > $inactive) 
    {
        session_destroy();
        header("Location: index.php");
        return;
    }
}
$_SESSION["timeout"] = time();

// controllo sul valore di sessione
if (!isset($_SESSION['login']))
{
    // reindirizzamento alla homepage in caso di login mancato
    session_destroy();
    header("Location: index.php");
    return;
}


?>

<!DOCTYPE html>
<html lang="it">

<head>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" href="coding-c-plus-plus-16.png">

<title>
<?php echo "$SITE_TITLE: Dashboard"; ?>
</title>

</head>

<body>

<?php
// OPERAZIONI INIZIALI
require "include/config.php";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() );
}

?>

Ciao, <?php echo $_SESSION['name'] ?>! <br>

<a href="/coding/logout.php">Logout</a>

<hr> <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<?php

$student = $_POST["student"];
$task = $_POST["task"];
$contest = $_POST["contest"];
$data = date("Y-m-d H:i:s");

$sql = "INSERT INTO tbl_delivery (deliverDate, student, task, contest) VALUES ( '$data' , '$student' , '$task' , '$contest' )";

$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if( !$result )
{    
    echo "oh oh...";
    return;
}

$sql = "SELECT id FROM tbl_delivery WHERE deliverDate='$data' AND student='$student'";
$res = mysqli_query($conn, $sql);

if ( !$res )
{
    echo "oh oh oh...";
    return;
}

$row = mysqli_fetch_assoc($res);

$fileName = $row["id"] . ".cpp";

// ------------------------------------------------------------------------------------------
// UPLOAD FILE SCRIPT
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$finalDestination = $target_dir . $fileName;
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if file already exists
if (file_exists($target_file)) 
{
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 100000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($fileType != "cpp") {
    echo "Sorry, only cpp files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) 
{
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} 
else 
{
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $finalDestination)) 
    {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } 
    else 
    {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

</body>

</html>
