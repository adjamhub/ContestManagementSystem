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

?>

<div class="w3-bar w3-dark-gray">

<a class="w3-bar-item w3-button" href="/coding/home.php"><?php echo $_SESSION['name'].' '.$_SESSION['surname'].', '.$_SESSION['class']?></a>

<a class="w3-bar-item w3-button w3-right" href="/coding/logout.php"><b>Logout</b></a>

</div>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<div class="w3-container">

<div class="w3-twothird">

<div class="w3-card-4 w3-light-blue w3-margin w3-padding">
<div class="w3-container">
<h2>Compiti per casa</h2>
<?php
$sql = "SELECT * FROM tbl_contest WHERE class='".$_SESSION['class']."' ORDER BY date_finish DESC";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    while( $row = mysqli_fetch_assoc($result) )
    {
        $v = $row["date_finish"];
        $id = $row["id"];
        echo "Compiti da consegnare entro $v";
        echo "<form action=\"contest.php\" method=\"post\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
        echo "<input type=\"submit\" value=\"Vai!\">";
        echo "</form>";
        
    }
}
else
{
    echo "NO TOPIC. STRANGE :(";
}
?>

</div>
</div>

</div> <!-- left side ends -->


<div class="w3-third"> <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->


<div class="w3-card-4 w3-light-green w3-margin w3-padding">
<div class="w3-container">
<h2>Materiale</h2>
<h3>Intro</h3>
QtCreator<br>
Coding Style<br>

<h3>Base Programming</h3>
Primi Elementi<br>
Strutture di Controllo<br>
Array<br>
Struct<br>
File di Testo<br>

<h3>OOP</h3>
Introduzione<br>
Ereditarietà<br>
Polimorfismo<br>
</div>
</div>


<div class="w3-card-4 w3-yellow w3-margin w3-padding">
<div class="w3-container">
<h2>Esercizi Liberi</h2>
<?php
$sql = "SELECT argument FROM tbl_topic";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    while( $row = mysqli_fetch_assoc($result) )
    {
        $v = $row["argument"];
        echo "$v<br>";
    }
}
else
{
    echo "NO TOPIC. STRANGE :(";
}
?>
</div>
</div>

</div> <!-- right side ends -->


</div> <!-- w3-container -->

</body>

</html>
