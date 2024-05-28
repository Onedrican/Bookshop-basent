<?php
session_start();
if (!isset($_SESSION["is_logged_in"]) || $_SESSION["is_logged_in"] === false) {
    header('location: login.php');
    die();
}
//Connection to the database
$servername = "127.0.0.1:3306";
$username = "rundb";
$password = "runpass";
$conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $geburtstag = $_POST['Geburtstag'];
    $vorname = htmlspecialchars(trim($_POST['Vorname_add']));
    $name = htmlspecialchars(trim($_POST['Name_add']));
    $email = htmlspecialchars(trim($_POST['Email_add']));
    $kontakt = $_POST['Kontakt'];
    $geschlecht = $_POST['Geschlecht_add'];
    $mitgliedSeit = $_POST['MitgliedSeit'];


    /* Da in ihrer Datenbank die Spalte "kid" nicht autoincrement ist
     muss ich die höchste kid auslesen und um 1 erhöhen damit man eine anständige kid hat */

    //Create querry for kid
    $querykid = "SELECT MAX(kid) as maxKid FROM kunden";

    // Prepare and execute the statement
    $stmt = $conn->prepare($querykid);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the highest kid and add 1
    $kid = $result['maxKid'] + 1;

    // Validate the inputs
    if (strlen($vorname) < 2 || strlen($vorname) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }
    if (strlen($name) < 2 || strlen($name) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }
    if (strlen($email) < 5 || strlen($email) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }


    $query = "INSERT INTO kunden (kid ,geburtstag, vorname, name, email, kontaktpermail, geschlecht, kunde_seit) VALUES (:kid, :geburtsdatum, :vorname, :name, :email, :kontakt, :geschlecht, :mitgliedSeit)";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':kid', $kid);
    $stmt->bindParam(':geburtsdatum', $geburtstag);
    $stmt->bindParam(':vorname', $vorname);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':kontakt', $kontakt);
    $stmt->bindParam(':geschlecht', $geschlecht);
    $stmt->bindParam(':mitgliedSeit', $mitgliedSeit);


    // Execute the query
    $stmt->execute();

}


include ("includesite.php");
if (isset($_GET['signout'])) {
    //Reset Session variabel
    $_SESSION = array();

    //Destruction of the coockies and session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // $_Session= array();
    // session_destroy();

    // Redirect to login
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <header>
        <span id = "name">Bookshop BASENT</span>
        <div class="dropdown">
            <div  class="burger"><p></p></div>
            <div  class="burger"></div>
            <div  class="burger"></div> 
            <div class="dropdown1">
            <div class="dropdown-content">
            <a href="index.php">Home</a><br>
            <a href="Ueber_uns.php">Über Uns</a><br>
            <a href="rechtliches.php">Rechtliches</a><br>
            <a href="login.php">Admin Login</a>
            </div>  
            </div>
        </div>
    </header>
    <br>
<div>
    <form method="post">
        <label for="Geb">Geburtsdatum</label>
        <input type="date" id="Geb" name="Geburtstag" required>
        <input type="text" name="Vorname_add" placeholder="Vorname" minlength="2" maxlength="45" required><br>
        <input type="text" name="Name_add" placeholder="Nachname" minlength="2" maxlength="45" required><br>

        <input type="text" name="Email_add" placeholder="E-Mail" minlength="5" maxlength="45" required><br>

        <label for="Kontakt">Kontakt per Email erwünscht?</label>
        <input type="radio"id="1" name="Kontakt" value="1" required><br>
        <label for="1">Ja</label><br>
        <input type="radio" id="0" name="Kontakt" value="0" required>
        <label for="0">Nein</label><br>

        <label for="Geschlecht">Geschlecht</label>
        <select id="Geschlecht" name="Geschlecht_add" size="2" required>
            <option value="M">Man</option>
            <option value="W">Frau</option>
        </select>
        <label for="Datum">Mitglied Seit</label>
        <input type="date" id="Datum" name="MitgliedSeit" required>
        <input type="submit" value="Submit">
    </form>
</div>

<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>