<?php

require('include/config.php');

// inizializzazione della sessione
session_start();
header("Cache-control: private");

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

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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

$id_cont = $_POST["id"];
?>

<div class="w3-bar w3-dark-gray">

<a class="w3-bar-item w3-button" href="/coding/home.php"><?php echo $_SESSION['name'].' '.$_SESSION['surname'].', '.$_SESSION['class']?></a>

<a class="w3-bar-item w3-button w3-right" href="/coding/logout.php"><b>Logout</b></a>
<a class="w3-bar-item w3-button w3-right" href="/coding/dashboard.php"><b>Dashboard</b></a>

</div>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->


<?php
$sql = "SELECT * FROM tbl_contest WHERE id=$id_cont";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    $row = mysqli_fetch_assoc($result);
    echo "ID: ".$row["id"]."<br>";
    echo "Descrizione: ".$row["description"]."<br>";
    echo "Consegna entro: ".$row["date_finish"]."<br>";
    echo "Classe: ".$row["class"]."<br>";
}
else
{
    echo "NO CONTEST. OOOOOOH  :(";
}
?>

<hr>
<!-- ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->

<?php
$sql = "SELECT * FROM tbl_cont_task JOIN tbl_task ON keyword = tbl_cont_task.task WHERE contest=$id_cont";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    while ($row = mysqli_fetch_assoc($result))
    {
        $task = $row["task"];
        echo "Esercizio: $task<br>";
        $file = $TASKS_BASE_URL.$row["topic"]."/".$task.".pdf";
        echo "File: <a href=\"$file\">Link</a><br>";
        
        echo "<form action=\"deliver.php\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<input type=\"hidden\" name=\"student\" value=\"".$_SESSION['login']."\">";
        echo "<input type=\"hidden\" name=\"task\" value=\"$task\">";
        echo "<input type=\"hidden\" name=\"contest\" value=\"$id_cont\">";
        echo "<input type=\"file\" name=\"fileToUpload\" size=\"40\">";
        echo "<input type=\"submit\" value=\"INVIA\">";
        
        echo "</form>";
        echo "<hr>";
    }
}
else
{
    echo "NO TASKs in THIS CONTEST. OOOOOOH  :(";
}
?>




</body>

</html>
