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


if (isset($_GET['id'])) {
    //$bookId = $_GET['id'];
    //$bookKatalog = $_GET['katalog'];

    // Fetch the book data from the database
    $stmt = $conn->prepare("SELECT * FROM buecher WHERE id = :id");
    $stmt->execute(['id' => $bookId]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        echo "<form method='post'>
    <input type='hidden' name='book_id' value='" . $book['id'] . "'>

    <label for='katalog'>Katalog:</label>
    <textarea id='katalog' name='katalog'>" . $book['katalog'] . "</textarea><br>

    <label for='nummer'>Nummer:</label>
    <textarea id='nummer' name='nummer'>" . $book['nummer'] . "</textarea><br>

    <label for='kurztitle'>Kurztitle:</label>
    <textarea id='kurztitle' name='kurztitle'>" . $book['kurztitle'] . "</textarea><br>

    <label for='kategorie'>Kategorie:</label>
    <textarea id='kategorie' name='kategorie'>" . $book['kategorie'] . "</textarea><br>

    <label for='verkauft'>Verkauft (1/0):</label>
    <input type='text' id='verkauft' name='verkauft' value='" . $book['verkauft'] . "'><br>

    <label for='kaufer'>Käufer:</label>
    <textarea id='kaufer' name='kaufer'>" . $book['kaufer'] . "</textarea><br>

    <label for='autor'>Autor:</label>
    <textarea id='autor' name='autor'>" . $book['autor'] . "</textarea><br>

    <label for='title'>Title:</label>
    <textarea id='title' name='title'>" . $book['title'] . "</textarea><br>

    <label for='sprache'>Sprache:</label>
    <textarea id='sprache' name='sprache'>" . $book['sprache'] . "</textarea><br>

    <label for='verfasser'>Verfasser:</label>
    <textarea id='verfasser' name='verfasser'>" . $book['verfasser'] . "</textarea><br>

    <label for='zustand'>Zustand:</label>
    <select name='zustand' id='zustand'>
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
<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>