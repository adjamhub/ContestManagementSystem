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
<a href="/coding/admin/">Home</a>
<br>
<a href="/coding/admin/contests.php">Gestisci Contest</a>
<br>

<hr>

<h2>Elenco Studenti</h2>

<?php

require "../include/config.php";

$fields = array("user", "pass", "name", "surname", "class");

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() );
}

$sql = "SELECT * FROM tbl_student";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    echo "<table>";
    echo "<tr>";
    foreach($fields as $value)
    {
        echo "<th>$value</th>";
    }
    echo "</tr>";

    while( $row = mysqli_fetch_assoc($result) )
    {
        echo "<tr>";
        foreach($fields as $value)
        {
            echo "<td>$row[$value]</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
}

?>

<hr>

<h2>Aggiungi Studente</h2>

<form method="post" action="addstudent.php">
Nome: <input type="text" name="name"><br>
Cognome: <input type="text" name="surname"><br>
User: <input type="text" name="user"><br>
Pass: <input type="password" name="pass"><br>
Conferma Pass: <input type="password" name="passCheck"><br>
Classe: 
<?php

$sql = "SELECT id FROM tbl_class";
$result = mysqli_query($conn, $sql);

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    echo "<select name=\"class\">";

    while( $row = mysqli_fetch_assoc($result) )
    {
        $v = $row["id"];
        echo "<option value=$v>$v</option>";
    }
    
    echo "</select>";
}
else
{
    echo "No class available :(";
}

?>
<br>

<input type="submit" value="aggiungi">

</form>

<hr>

<h2>Elenco Classi</h2>

<?php

$sql = "SELECT * FROM tbl_class";
$result = mysqli_query($conn, $sql);

$fields = array("id", "year", "section");

// controllo sul risultato dell'interrogazione
if(mysqli_num_rows($result) > 0 )
{    
    echo "<table>";
    echo "<tr>";
    foreach($fields as $value)
    {
        echo "<th>$value</th>";
    }
    echo "</tr>";

    while( $row = mysqli_fetch_assoc($result) )
    {
        echo "<tr>";
        foreach($fields as $value)
        {
            echo "<td>$row[$value]</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
}
?>

<hr>

<h2>Aggiungi Classe</h2>

<form method="post" action="addclass.php">
ID (es: 3AS): <input type="text" name="id"><br>
Anno (es: 3): <input type="text" name="year"><br>
Sezione (es: AS): <input type="text" name="section"><br>

<input type="submit" value="aggiungi">

</form>

</body>

</html>
