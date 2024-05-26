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
    <input type="text" name="search" placeholder="Suchen" minlength="1" maxlength="50" required>
    <select name="filter">
        <option value="">Filtern</option>
        <option value="vorname">Vorname</option>
        <option value="name">Name</option>
        <option value="email">Email</option>
    </select>
    <input type="submit" value="Submit">
</form>

<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>

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
    if (isset($_POST['delete'])) {
        $kundeID = $_POST['delete'];
        $deleteQuery = "DELETE FROM kunden WHERE id = :id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindValue(':id', $kundeID, PDO::PARAM_INT);
        $deleteStmt->execute();
    } else {
        $search = htmlspecialchars(trim($_POST['search']));
        $filter = $_POST['filter'];

        // Validate the input
        if (strlen($search) < 1 || strlen($search) > 50) {
            echo "Invalid input. Please enter a string with a length between 1 and 50.";
            return;
        }

        //Preparing beginning of querry
        $search = $search .= "%";
        $query = "SELECT * FROM kunden WHERE ";

        // Determine the filter
        switch ($filter) {
            case 'vorname':
                $query .= "vorname LIKE :search";
                break;
            case 'name':
                $query .= "name LIKE :search";
                break;
            case 'email':
                $query .= "email LIKE :search";
                break;
            default:
                $query .= "name LIKE :search";
                break;
        }

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        $stmt->execute();

        //Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $index => $kunde) {
            $results[$index]['kontaktpermail'] = $kunde['kontaktpermail'] == 1 ? 'Ja' : 'Nein';
        }
        //Display the results
        echo "<div>";
        if (count($results) > 0) {
            foreach ($results as $kunde) {
                echo '<div class="result_box">';
                echo '<span class="user"><img class="bild1" src=pictures/kunde_icon.webp alt="Kunde_Icon"></span>';
                echo "<p>Kunden Id: " . $kunde['kid'] . "</p>";
                echo "<p>Vorname: " . $kunde['vorname'] . "</p>";
                echo "<p>Name: " . $kunde['name'] . "</p>";
                echo "<p>Geschlecht: " . $kunde['geschlecht'] . "</p>";
                echo "<p>Email: " . $kunde['email'] . "</p>";
                echo "<p>Kunde seit: " . $kunde['kunde_seit'] . "</p>";
                echo "<p>Geburtstag: " . $kunde['geburtstag'] . "</p>";
                echo "<p>Konakt per E-Mail erwünscht: " . $kunde['kontaktpermail'] . "</p>";
                echo '<form method="POST"><button type="submit" name="delete" value="' . $kunde['kid'] . '" onclick="return confirm(\'Möchten sie diesen Kunden wirklich löschen?\')">Delete</button></form>';
                echo "</div>";
            }
        } else {
            echo '<div id= "noresults">';
            echo "No results found :(";
            echo '</div>';
        }
        echo "</div>";
    }
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
</body>
</html>