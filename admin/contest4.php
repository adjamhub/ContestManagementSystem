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

?>

<h2>Finally link tasks to the contest :)</h2>

<form method="post" action="contest5.php">

<?php
$sql = "SELECT keyword FROM tbl_task WHERE topic='$topic'";

$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    while( $row = mysqli_fetch_assoc($result) )
    {
        $v = $row["keyword"];
        echo "<input type='checkbox' name=$v>$v<br>";
    }
}
else
{
    echo "NO TASK FOR TOPIC $topic :(";
}

?>
<br>
<br>
<input type="hidden" name="topic" value="<?php echo $topic; ?>">
<input type="hidden" name="contest" value="<?php echo $contest; ?>">
<input type="submit" value="CLICCA QUI E HAI FINITO :)">
</form>
</body>

</html>
