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
<a href="/coding/admin/">Home</a>
<br>
<a href="/coding/admin/students.php">Gestisci Studenti</a>
<br>
<a href="/coding/admin/contests.php">Gestisci Contest</a>
<br>

<hr>

<a href="createTaskFolders.php">Create Tasks Folders</a><br>
<a href="updateTasks.php">Update Tasks List</a><br>
<hr>

<?php
// OPERAZIONI INIZIALI
require "../include/config.php";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() );
}

?>

<!-- ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->

<h1>Tasks per Topic ;)</h1>

<?php
$sql = "SELECT * FROM tbl_topic";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    while( $row = mysqli_fetch_assoc($result) )
    {
        $arg = $row["argument"];
        echo "<h2>".$arg."</h2>";
        $key = $row["keyword"];
        
        $sql = "SELECT keyword FROM tbl_task WHERE topic='$key'";
        $res = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($res) > 0 )        
        {
            while( $list = mysqli_fetch_assoc($res) )
            {
                $task = $list["keyword"];
                echo "$task<br>";
                $file = $TASKS_BASE_URL.$key."/".$task.".pdf";
                echo "<a href=\"$file\">link</a><br>";
            }
        }
        else
        {
            echo "NO tasks for topic $arg"; 
        }
        
    }
}
else
{
    echo "NO TOPIC. STRANGE :(";
}

?>


</body>

</html>
