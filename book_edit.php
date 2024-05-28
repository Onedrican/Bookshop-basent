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
    $bookId = htmlspecialchars(trim($_POST['book_id']));
    $katalog = htmlspecialchars(trim($_POST['katalog']));
    $nummer = htmlspecialchars(trim($_POST['nummer']));
    $kurztitle = htmlspecialchars($_POST['kurztitle']);
    $kategorie = htmlspecialchars(trim($_POST['kategorie']));
    $verkauft = htmlspecialchars(trim($_POST['verkauft']));
    $kaufer = htmlspecialchars(trim($_POST['kaufer']));
    $autor = htmlspecialchars($_POST['autor']);
    $title = htmlspecialchars($_POST['title']);
    $sprache = htmlspecialchars(trim($_POST['sprache']));
    $verfasser = htmlspecialchars(trim($_POST['verfasser']));
    $zustand = $_POST['zustand'];

    // Validate the inputs
    if (!is_numeric($katalog) || $katalog < 10 || $katalog > 19) {
        echo "Invalid Katalog input. Please enter a number between 10 and 19.";
        return;
    }

    if (!is_numeric($nummer) || $nummer < 1 || $nummer > 800) {
        echo "Invalid Nummer input. Please enter a number between 1 and 800.";
        return;
    }

    if (strlen($kurztitle) < 1 || strlen($kurztitle) > 999999999999999999999999) {
        echo "Invalid Kurztitle input. Please enter a string with a length between 1 and 999999999999999999999999.";
        return;
    }

    if (!is_numeric($kategorie) || $kategorie < 1 || $kategorie > 14) {
        echo "Invalid Kategorie input. Please enter a number between 1 and 14.";
        return;
    }

    if (!is_numeric($verkauft) || $verkauft < 0 || $verkauft > 1) {
        echo "Invalid Verkauft input. Please enter 0(false) or 1(true).";
        return;
    }

    if (!is_numeric($kaufer) || $kaufer < 0 || $kaufer > 100000) {
        echo "Invalid Verkauft input. Please enter a number between 1 or 100'000).";
        return;
    }

    if (strlen($autor) < 1 || strlen($autor) > 100) {
        echo "Invalid Autor input. Please enter a string with a length between 1 and 100.";
        return;
    }

    if (strlen($title) < 1 || strlen($title) > 999999999999999999999999) {
        echo "Invalid Title input. Please enter a string with a length between 1 and 999999999999999999999999.";
        return;
    }

    if (strlen($sprache) < 1 || strlen($sprache) > 50) {
        echo "Invalid Sprache input. Please enter a string with a length between 1 and 50.";
        return;
    }

    if (!is_numeric($verfasser) || $verfasser < 1 || $verfasser > 6) {
        echo "Invalid Verfasser input. Please enter a number between 1 and 6.";
        return;
    }

    if (!in_array($zustand, ['M', 'S', 'G'])) {
        echo "Invalid Zustand input. Please enter either 'M', 'S', or 'G'.";
        return;
    }

    $stmt = $conn->prepare("UPDATE buecher SET katalog = :katalog, nummer = :nummer, kurztitle = :kurztitle, kategorie = :kategorie, verkauft = :verkauft, kaufer = :kaufer, autor = :autor, title = :title, sprache = :sprache, verfasser = :verfasser, zustand = :zustand WHERE id = :id");
    $stmt->execute(['katalog' => $katalog, 'nummer' => $nummer, 'kurztitle' => $kurztitle, 'kategorie' => $kategorie, 'verkauft' => $verkauft, 'kaufer' => $kaufer, 'autor' => $autor, 'title' => $title, 'sprache' => $sprache, 'verfasser' => $verfasser, 'zustand' => $zustand, 'id' => $bookId]);

    echo "Book updated successfully.";
}


if (isset($_GET['go_back'])) {
    header('Location: Book_change.php');
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

    $_SESSION = array();
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
    <button type="submit" name='go_back'> Go back</button>
    </form>

<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>