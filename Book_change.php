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
<form method="post">
    <input type="text" name="search" placeholder="Suchen" minlength="1" maxlength="50" required>
    <select name="sort">
        <option value="">Sortieren</option>
        <option value="kurztitle_asc">Name A-Z</option>
        <option value="kurztitle_desc">Name Z-A</option>
        <option value="autor_asc">Author A-Z</option>
        <option value="autor_desc">Author Z-A</option>
        <option value="nummer_asc">Nummer Aufsteigend</option>
        <option value="nummer_desc">Number Absteigend</option>
    </select>
    <select name="filter">
        <option value="">Filtern</option>
        <option value="autor">Author</option>
        <option value="title">Title</option>
        <option value="kategorie">Kategorie</option>
        <option value="kurztitle">Kurztitle</option>
    </select>
    <input type="submit" value="Submit">
</form>



<form method="get">
    <button type="submit" name='signout' class="signhh"> Sign Out</button>
</form>

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = htmlspecialchars(trim($_POST['search']));
    $sort = $_POST['sort'];
    $filter = $_POST['filter'];

    //Preparing beginning of querry
    $search = $search .= "%";
    $query = "SELECT * FROM buecher WHERE ";

    // Validate the input
    if (strlen($search) < 1 || strlen($search) > 50) {
        echo "Invalid input. Please enter a string with a length between 1 and 50.";
        return;
    }

    // Determine the filter
    switch ($filter) {
        case 'autor':
            $query .= "autor LIKE :search";
            break;
        case 'title':
            $query .= "title LIKE :search";
            break;
        case 'kategorie':
            $query .= "kategorie LIKE :search";
            break;
        case 'kurztitle':
            $query .= "kurztitle LIKE :search";
            break;
        default:
            $query .= "kurztitle LIKE :search";
            break;
    }

    // Determine the sort order
    switch ($sort) {
        case 'kurztitle_asc':
            $query .= " ORDER BY kurztitle ASC";
            break;
        case 'kurztitle_desc':
            $query .= " ORDER BY kurztitle DESC";
            break;
        case 'autor_asc':
            $query .= " ORDER BY autor ASC";
            break;
        case 'autor_desc':
            $query .= " ORDER BY autor DESC";
            break;
        case 'nummer_asc':
            $query .= " ORDER BY nummer ASC";
            break;
        case 'nummer_desc':
            $query .= " ORDER BY nummer DESC";
            break;
        default:
            $query .= " ORDER BY kurztitle ASC";
            break;
    }


    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', "$search", PDO::PARAM_STR);
    $stmt->execute();

    //Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Display the results
    echo "<div>";
    if (count($results) > 0) {
        foreach ($results as $book) {
            echo '<div class="result_box">';
            echo '<span class="book1"><img class="bild1" src=pictures/book.webp alt="bookcover"></span>';
            $kurztitle = substr($book['kurztitle'], 0, 20); // Limit the output to the first 20 characters
            echo "<h2>" . $kurztitle . "</h2>";
            echo "<p>Author: " . $book['autor'] . "</p>";
            echo "<form action='book_edit.php' method='GET'>
            <button type='submit'
                    name='id'
                    value='" . $book['id'] . "'
                    Edit>
                    Buch ändern
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
</body>
</html>