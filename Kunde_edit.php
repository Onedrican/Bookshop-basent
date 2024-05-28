<?php
session_start();
if (!isset($_SESSION["is_logged_in"]) || $_SESSION["is_logged_in"] === false) {
    header('location: login.php');
    die();
}

//error_reporting(E_ERROR | E_PARSE);

//Connection to the database
$servername = "127.0.0.1:3306";
$username = "rundb";
$password = "runpass";
$conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['go_back'])) {
    header('Location: User_change.php');
}

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

    $_Session= array();
    session_destroy();

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

<?php
if (isset($_GET['kid'])) {
    $kundenid = $_GET['kid'];

    // Fetch the book data from the database
    $stmt = $conn->prepare("SELECT * FROM kunden WHERE kid = :kid");
    $stmt->execute(['kid' => $kundenid]);
    $kunden = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kunden) {
        echo "<form method='post'>
    <input type='hidden' name='kunden_id' value='" . $kunden['kid'] . "'>
   
     <label for='Geb'>Geburtsdatum</label>
     <input type='date' id='Geb' name='Geb' value='" . $kunden['geburtstag'] . "' required><br>
     
     <label for='vorname'>Vorname</label>
     <input type='text' id='vorname' name='vorname' value='" . $kunden['vorname'] . "' minlength='2' maxlength='50' required><br>
     
     <label for='name'>Name</label>
     <input type='text' id='name' name='name' value='" . $kunden['name'] . "' minlength='2' maxlength='50' required><br>
     
     <label for='Geschlecht'>Geschlecht</label>
     <select id='Geschlecht' name='Geschlecht' size='2' required><br>
     <option value='M'>Mann</option>
     <option value='W'>Frau</option>
     </select>

     <label for='Datum'>Mitglied seit: </label>
     <input type='date' id='Datum' name='Datum' value='" . $kunden['kunde_seit'] . "' required><br>
     
     <label for='email'>Email?</label>
     <input type='email' id='email' name='email' value='" . $kunden['email'] . "' minlength='5' maxlength='150' required><br>
     
     <label for='Kontakt'>Kontakt per Email erwünscht?</label>
        <input type='radio' id='1' name='Kontakt' value='1' required><br>
        <label for='1'>Ja</label><br>
        <input type='radio' id='0' name='Kontakt' value='0' required>
        <label for='0'>Nein</label><br>
        
    <input type='submit' value='Update'>
    </form>";
    } else {
        echo "Kunde not found.";
    }
} else {
    echo "No Kunden ID provided.";
}

if (isset($_POST['kunden_id'])) {
    $kudenid = htmlspecialchars(trim($_POST['kunden_id']));
    $vorname = htmlspecialchars(trim($_POST['vorname']));
    $name = htmlspecialchars(trim($_POST['name']));
    $geburtstag = htmlspecialchars(trim($_POST['Geb']));
    $geschlecht = htmlspecialchars(trim($_POST['Geschlecht']));
    $email = htmlspecialchars(trim($_POST['email']));
    $kunde_seit = htmlspecialchars(trim($_POST['Datum']));
    $kontakt = $_POST['Kontakt'];


    // Validate the inputs
    if (strlen($vorname) < 2 || strlen($vorname) > 50) {
        echo "Invalid Vorname input. Please enter a string with a length between 2 and 50.";
        return;
    }
    if (strlen($name) < 2 || strlen($name) > 50) {
        echo "Invalid Name input. Please enter a string with a length between 2 and 50.";
        return;
    }
    if (strlen($geburtstag) < 1) {
        echo "Invalid Geburtstag input. Please enter a valid date.";
        return;
    }
    if ($geschlecht != 'M' && $geschlecht != 'W') {
        echo "Invalid Geschlecht input. Please enter 'M' for Mann or 'W' for Frau.";
        return;
    }
    if (strlen($email) < 5 || strlen($email) > 150) {
        echo "Invalid Email input. Please enter a string with a length between 5 and 150.";
        return;
    }
    if (strlen($kunde_seit) < 1) {
        echo "Invalid Mitglied seit input. Please enter a valid date.";
        return;
    }
    if ($kontakt != 0 && $kontakt != 1) {
        echo "Invalid Kontakt input. Please enter '0' for Nein or '1' for Ja.";
        return;
    }

    $stmt = $conn->prepare("UPDATE kunden SET vorname = :vorname, name = :name, geburtstag = :geburtstag, geschlecht = :geschlecht, email = :email, kunde_seit = :kunde_seit, kontaktpermail = :kontakt WHERE kid = :kid");
    $stmt->execute(['vorname' => $vorname, 'name' => $name, 'geburtstag' => $geburtstag, 'geschlecht' => $geschlecht, 'email' => $email, 'kunde_seit' => $kunde_seit, 'kontakt' => $kontakt, 'kid' => $kudenid]);

    echo "Kunde updated successfully.";
}

?>
<form method="get">
    <button type="submit" name='go_back'> Go back</button>
</form>

<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>
