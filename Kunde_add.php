<?php

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
    $geschlecht = $_POST['Geschlecht_add'];
    $mitgliedSeit = $_POST['MitgliedSeit'];

    // Validate the inputs
    if (strlen($vorname) < 2 || strlen($vorname) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }
    if (strlen($name) < 2 || strlen($name) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }

    $query = "INSERT INTO kunden (geburtstag, vorname, name, geschlecht, kunde_seit) VALUES (:geburtsdatum, :vorname, :name, :geschlecht, :mitgliedSeit)";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':geburtsdatum', $geburtstag);
    $stmt->bindParam(':vorname', $vorname);
    $stmt->bindParam(':name', $name);
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

<div>
    <form method="post">
        <label for="Geb">Geburtsdatum</label>
        <input type="date" id="Geb" name="Geburtstag" required>
        <input type="text" name="Vorname_add" placeholder="Vorname" minlength="2" maxlength="45" required><br>
        <input type="text" name="Name_add" placeholder="Nachname" minlength="2" maxlength="45" required><br>
        <label for="Geschlecht">Geschlecht</label>
        <select id="Geschlecht" name="Geschlecht_add" size="3" required>
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