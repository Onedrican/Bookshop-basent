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
    <p id="title">User bearbeiten</p>
    <form method="get">
        <button type="submit" name='signout' class="signhh"> Sign Out</button>
    </form>
<div>
<hr>
    <?php
    if (isset($_GET['id'])) {
        $userid = $_GET['id'];

        // Fetch the book data from the database
        $stmt = $conn->prepare("SELECT * FROM benutzer WHERE id = :id");
        $stmt->execute(['id' => $userid]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user) {
            echo "<form method='post' name='hinzufuegen'>
    <input type='hidden' name='user_id' value='" . $user['ID'] . "'>
   <br>
     <label for='username'>Benutzername</label>
     <input type='text' id='username' name='username' value='" . $user['benutzername'] . "' minlength='2' maxlength='45' required><br>
     <br>
     <label for='name'>Name</label>
     <input type='text' id='name' name='name' value='" . $user['name'] . "' minlength='2' maxlength='45' required><br>
     <br>
     <label for='vorname'>Vorname</label>
     <input type='text' id='vorname' name='vorname' value='" . $user['vorname'] . "' minlength='2' maxlength='45' required><br>
     <br>
     <label for='email'>E-Mail</label>
     <input type='email' id='email' name='email' value='" . $user['email'] . "' minlength='5' maxlength='100' required><br>
     <br>
        <label for='admin'>Admin?</label><br>
        <input type='radio' id='admin' name='admin' value='1' required>
        <label for='admin'>Ja</label><br>
        <input type='radio' id='admin' name='admin' value='0' required>
        <label for='admin'>Nein</label><br>
        <br>
    <input type='submit' value='Update'>
    </form>";
        } else {
            echo "User not found.";
        }
    } else {
        echo "No User ID provided.";
    }

    if (isset($_POST['user_id'])) {
        $userid = htmlspecialchars(trim($_POST['user_id']));
        $benutzername = htmlspecialchars(trim($_POST['username']));
        $name = htmlspecialchars(trim($_POST['name']));
        $vorname = htmlspecialchars(trim($_POST['vorname']));
        $email = htmlspecialchars(trim($_POST['email']));
        $admin = $_POST['admin'];

        // Validate the inputs
        if (strlen($benutzername) < 2 || strlen($benutzername) > 45) {
            echo "Invalid Benutzername input. Please enter a string with a length between 2 and 45.";
            return;
        }
        if (strlen($name) < 2 || strlen($name) > 45) {
            echo "Invalid Name input. Please enter a string with a length between 2 and 50.";
            return;
        }
        if (strlen($vorname) < 2 || strlen($vorname) > 50) {
            echo "Invalid Vorname input. Please enter a string with a length between 2 and 45.";
            return;
        }
        if (strlen($email) < 5 || strlen($email) > 100) {
            echo "Invalid Kurztitle input. Please enter a string with a length between 5 and 100.";
            return;
        }

        $stmt = $conn->prepare("UPDATE benutzer SET benutzername = :benutzername, name = :name, vorname = :vorname, email = :email, admin = :admin WHERE id = :id");
        $stmt->execute(['benutzername' => $benutzername, 'name' => $name, 'vorname' => $vorname, 'email' => $email, 'admin' => $admin, 'id' => $userid]);

        echo "User updated successfully.";
    }
    ?>

    <form method="get">
        <button type="submit" name='go_back'> Go back</button>
    </form>


</body>
</html>