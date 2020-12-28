<?php

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
        header("Location: /coding/index.php");
        return;
    }
}

$_SESSION["timeout"] = time();

// controllo sul valore di sessione
if (!isset($_SESSION['login']) || !isset($_SESSION['admin']))
{
    // reindirizzamento alla homepage in caso di login mancato
    session_destroy();
    header("Location: /coding/index.php");
    return;
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title> adjam coding: administration</title>
</head>

<body>

<a href="/coding/logout.php">Logout</a>
<br>
<a href="/coding/admin/tasks.php">Gestisci Esercizi</a>
<br>
<a href="/coding/admin/students.php">Gestisci Studenti</a>
<br>
<a href="/coding/admin/">Home</a>
<br>

<hr>

<?php
// OPERAZIONI INIZIALI
require "../include/config.php";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() );
}

$contest = $_POST["contest"];
$topic = $_POST["topic"];

$taskList = array();

$sql = "SELECT keyword FROM tbl_task WHERE topic='$topic'";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    while( $row = mysqli_fetch_assoc($result) )
    {
        $taskList[] = $row["keyword"];
    }
}
else
{
    echo "NO TASK FOR TOPIC $topic :(";
    return;
}

// NOW tthe task to add to the contest
$taskToAdd = array();

foreach ($taskList as $t)
{
    if ( isset( $_POST["$t"] ) )
    {
        $taskToAdd[] = $t;
    }
}

// Last, add them!!

foreach ($taskToAdd as $tta)
{
    $sql = "INSERT INTO tbl_cont_task (contest, task) VALUES ('$contest' , '$tta')";
    $result = mysqli_query($conn, $sql);
    if ($result)
    {
        echo "$tta added to contest $contest<br>";
    }
    else
    {
        echo "CAZZO!";
    }
}


?>

</body>

</html>
