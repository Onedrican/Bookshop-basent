<?php
include ("includesite.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = htmlspecialchars(trim($_POST['search']));

    $search = $search .= "%";
    $query = "SELECT * FROM buecher WHERE ";
    $query .= "kurztitle LIKE :search";
    $query .= " ORDER BY kurztitle ASC";
    echo $query;


    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', "$search", PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div>";
    if (count($results) > 0) {
        foreach ($results as $book) {
            echo '<div class="result_box">';
            echo '<span class="book1"><img class="bild1" src=pictures/book.webp alt="bookcover"></span>';
            $kurztitle = substr($book['kurztitle'], 0, 20); // Limit the output to the first 20 characters
            echo "<h2>" . $kurztitle . "</h2>";
            echo "<p>Author: " . $book['autor'] . "</p>";
            echo "<form action='alle_infos.php' method='GET'>
            <button type='submit'
                    name='id'
                    value='" . $book['id'] . "'
                    data-title='" . $book['kurztitle'] . "'
                    data-author='" . $book['autor'] . "'>
                    Details
            </button>
          </form>";
            echo "</div>";
        }
    } else {
        echo '<div id= "noresults">';
        echo "No results found :(";
        echo '</div>';
    }
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
<form method="post">
    <input type="text" name="search" placeholder="Suchen">
    <input type="submit" value="Submit">
</form>
<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>