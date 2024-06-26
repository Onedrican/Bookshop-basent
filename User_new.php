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
    <p id="titlea">Neuer User</p>
    <form method="get">
        <button type="submit" name='signout' class="signhh"> Sign Out</button>
    </form>
    <hr>
<div>
<form method="post" name="hinzufuegen">
    <div>
    <input type="text" name="Benutzername_add" placeholder="Benutzername" minlength="2" maxlength="45" required><br>
    <br>
    </div>
    <div>
    <input type="text" name="Name_add" placeholder="Name" minlength="2" maxlength="45" required><br>
    <br>
    </div>
    <div>
    <input type="text" name="Vorname_add" placeholder="Vorname" minlength="2" maxlength="45" required><br>
    <br>
    </div>
    <div>
    <input type="password" name="Password_add" placeholder="Password min.8" required minlength="8" maxlength="50"><br>
    <br>
    </div>
    <div>
    <input type="email" name="Email_add" placeholder="Email" required minlength="5" maxlength="100"><br>
    <br>
    </div>
    <div>
    <label for="Admin">Admin?</label><br>
    <input type="radio"id="admin1" name="admin" value="1" required>
    <label for="admin1">Ja</label><br>
    <input type="radio" id="admin0" name="admin" value="0" required>
    <label for="admin0">Nein</label><br>
    <br>
    </div>
    <div>
    <input type="submit" value="Submit">
    </div>
</form>
</div>
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
    $benutzername = htmlspecialchars(trim($_POST['Benutzername_add']));
    $name = htmlspecialchars(trim($_POST['Name_add']));
    $vorname = htmlspecialchars(trim($_POST['Vorname_add']));
    $password = htmlspecialchars(trim($_POST['Password_add']));
    $email = htmlspecialchars(trim($_POST['Email_add']));
    $admin = $_POST['admin'];

    // Validate the inputs
    if (strlen($benutzername) < 2 || strlen($benutzername) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }
    if (strlen($name) < 2 || strlen($name) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }
    if (strlen($vorname) < 2 || strlen($vorname) > 45) {
        echo "Invalid input. Please enter a string with a length between 2 and 45.";
        return;
    }
    if (strlen($password) < 8 || strlen($password) > 50) {
        echo "Invalid input. Please enter a string with a length of at least 8 and a maximum of 50.";
        return;
    }
    if (strlen($email) < 5 || strlen($email) > 100) {
        echo "Invalid input. Please enter a string with a length between 5 and 100.";
        return;
    }
    //Hashing password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    //Prepare the SQL query
    $query = "INSERT INTO benutzer (benutzername, name, vorname, passwort, email, admin ) VALUES (:benutzername, :name, :vorname, :password, :email, :admin)";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':benutzername', $benutzername);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':vorname', $vorname);
    $stmt->bindParam(':password', $hash);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':admin', $admin);

    // Execute the query
    $stmt->execute();
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

    // $_Session= array();
    // session_destroy();

    // Redirect to login
    header('Location: login.php');
    exit;
}
?>
</body>
</html>