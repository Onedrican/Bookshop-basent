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

if (isset($_POST['book_id'])) {
    $userid = htmlspecialchars(trim($_POST['user_id']));
    $benutzername = htmlspecialchars(trim($_POST['Benutzername_add']));
    $name = htmlspecialchars(trim($_POST['Name_add']));
    $vorname = htmlspecialchars(trim($_POST['Vorname_add']));
    $email = htmlspecialchars(trim($_POST['Email_add']));
    $admin = $_POST['admin'];

    // Validate the inputs
    if (strlen($benutzername) < 1 || strlen($benutzername) > 50) {
        echo "Invalid Benutzername input. Please enter a string with a length between 1 and 50.";
        return;
    }
    if (strlen($name) < 1 || strlen($name) > 50) {
        echo "Invalid Name input. Please enter a string with a length between 1 and 50.";
        return;
    }
    if (strlen($vorname) < 1 || strlen($vorname) > 50) {
        echo "Invalid Vorname input. Please enter a string with a length between 1 and 50.";
        return;
    }
    if (strlen($email) < 1 || strlen($email) > 100) {
        echo "Invalid Kurztitle input. Please enter a string with a length between 1 and 100.";
        return;
    }



    $stmt = $conn->prepare("UPDATE benutzer SET katalog = :katalog, nummer = :nummer, kurztitle = :kurztitle, kategorie = :kategorie, verkauft = :verkauft, kaufer = :kaufer, autor = :autor, title = :title, sprache = :sprache, verfasser = :verfasser, zustand = :zustand WHERE id = :id");
    $stmt->execute(['katalog' => $katalog, 'nummer' => $nummer, 'kurztitle' => $kurztitle, 'kategorie' => $kategorie, 'verkauft' => $verkauft, 'kaufer' => $kaufer, 'autor' => $autor, 'title' => $title, 'sprache' => $sprache, 'verfasser' => $verfasser, 'zustand' => $zustand, 'id' => $bookId]);

    echo "Book updated successfully.";
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
    if (isset($_GET['id'])) {
        $bookId = $_GET['id'];

        // Fetch the book data from the database
        $stmt = $conn->prepare("SELECT * FROM buecher WHERE id = :id");
        $stmt->execute(['id' => $bookId]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            echo "<form method='post'>
    <input type='hidden' name='book_id' value='" . $book['id'] . "'>

    <label for='katalog'>Katalog:</label>
    <input type='number' id='katalog' name='katalog' value='" . $book['katalog'] . "' min='10' max='19' required><br>

    <label for='nummer'>Nummer:</label>
    <input type='number' id='nummer' name='nummer' min='1' max='999' required value='" . $book['nummer'] . "'><br>

    <label for='kurztitle'>Kurztitle:</label>
    <textarea id='kurztitle' name='kurztitle' minlength='1' maxlength='999999999999999999999999'>" . $book['kurztitle'] . "</textarea><br>

    <label for='kategorie'>Kategorie:</label>
    <input type='number' id='kategorie' name='kategorie' value='" . $book['kategorie'] . "' min='1' max='14' required><br>

    <label for='verkauft'>Verkauft (1/0):</label>
    <input type='number' id='verkauft' name='verkauft' value='" . $book['verkauft'] . "' min='0' max='1' required><br>

    <label for='kaufer'>Käufer:</label>
    <input type='number' id='kaufer' name='kaufer' value='" . $book['kaufer'] . "' min='0' max='100000' required><br>

    <label for='autor'>Autor:</label>
    <textarea id='autor' name='autor' maxlength='100' minlength='1' required>" . $book['autor'] . "</textarea><br>

    <label for='title'>Title:</label>
    <textarea id='title' name='title' minlength='1' maxlength='999999999999999999999999' required>" . $book['title'] . "</textarea><br>

    <label for='sprache'>Sprache:</label>
    <textarea id='sprache' name='sprache' minlength='0' maxlength='50' required>" . $book['sprache'] . "</textarea><br>

    <label for='verfasser'>Verfasser:</label>
    <input type='number' id='verfasser' name='verfasser' value='" . $book['verfasser'] . "' min='1' max='6' required><br>

    <label for='zustand'>Zustand:</label>
    <select name='zustand' id='zustand' required>
        <option value='" . $book['zustand'] . "'>" . $book['zustand'] . "</option>
        <option value='M'>M</option>
        <option value='S'>S</option>
        <option value='G'>G</option>
    </select>

    <input type='submit' value='Update'>
    </form>";
        } else {
            echo "Book not found.";
        }
    } else {
        echo "No book ID provided.";

    }
    ?>
<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>