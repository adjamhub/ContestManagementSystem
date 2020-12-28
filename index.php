<?php 

require "include/config.php"; 

// ------------------------------------------------------------------------------------------

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
        header("Location: /coding/");
        return;
    }
}
$_SESSION["timeout"] = time();

// se sei già autenticato, vai SUBITO alla dashboard :)
if (isset($_SESSION['login']))
{
    header("Location: dashboard.php");
    return;
}

// ------------------------------------------------------------------------------------------

// controllo sul parametro d'invio
if(isset($_POST['submit']) && (trim($_POST['submit']) == "Login"))
{ 
    // validazione dei parametri tramite filtro per le stringhe
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    if ($username == $ADMIN_USER && $password == $ADMIN_PASS)
    {
        $_SESSION['login'] = "god";
        $_SESSION['admin'] = "god";
        header("Location: /coding/admin/");
        return;
    }

    // should we add this???
    //$password = sha1($password);
        
    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error() );
        return;
    }

    $sql = "SELECT * FROM tbl_student WHERE user = '$username' AND pass = '$password'";
    $result = mysqli_query($conn, $sql);

    $matches = mysqli_num_rows($result);

    // controllo sul risultato dell'interrogazione
    if( $matches != 1 )
    {
        // reindirizzamento alla homepage in caso di insuccesso
        $_SESSION['error'] = "azz";
        header("Location: /coding/");
        return;
    }
    else
    {
        // chiamata alla funzione per l'estrazione dei dati
        $res = mysqli_fetch_object($result);
        
        // creazione del valore di sessione
        $_SESSION['login'] = $res-> user;
        $_SESSION['name'] = $res-> name;
        $_SESSION['surname'] = $res-> surname;
        $_SESSION['class'] = $res-> class;
        
        // disconnessione da MySQL
        mysqli_close($conn);
        
        // reindirizzamento alla pagina di amministrazione in caso di successo
        header("Location: dashboard.php");
    }
}
else
{
?>

<!DOCTYPE html>
<html>

<head>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="icon" href="coding-c-plus-plus-16.png">

<title>
<?php echo $SITE_TITLE; ?>
</title>

</head>

<body style="background: #F5F5F5">


<div class="w3-display-middle">

<div class="w3-card-4 w3-white w3-padding-large">
<div class="w3-container w3-center">
<h3>adjam coding</h3>
</div>
<form class="w3-container w3-xlarge" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<br>

<?php
if ( isset($_SESSION['error']) )
{
    echo "<div class='w3-container w3-red'>";
    echo "<h3>Error!</h3>";
    echo "<p>Invalid credentials</p>";
    echo "</div>";
}
?>

<input class="w3-input" name="username" type="text" placeholder="Username" required>
<br>
<input class="w3-input" name="password" type="password" placeholder="Password" required>
<br>
<button class="w3-button w3-block w3-blue w3-section w3-padding" type="submit" name="submit" value="Login">Login</button>

</form>
</div>

</div> <!-- CARD -->

</body>

</html>

<?php
}
?>
