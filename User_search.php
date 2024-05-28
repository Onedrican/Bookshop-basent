<?php
include ("includesite.php");
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
    $search = htmlspecialchars(trim($_POST['search']));
    $filter = $_POST['filter'];

    // Validate the input
    if (strlen($search) < 1 || strlen($search) > 50) {
        echo "Invalid input. Please enter a string with a length between 1 and 50.";
        return;
    }

    //Preparing beginning of querry
    $search = $search .= "%";
    $query = "SELECT * FROM benutzer WHERE ";

    // Determine the filter
    switch ($filter) {
        case 'ID':
            $query .= "ID LIKE :search";
            break;
        case 'Username':
            $query .= "benutzername LIKE :search";
            break;
        case 'Name':
            $query .= "name LIKE :search";
            break;
        case 'Vorname':
            $query .= "vorname LIKE :search";
            break;
        case 'Email':
            $query .= "email LIKE :search";
        default:
            $query .= "benutzername LIKE :search";
            break;
    }

    // Execute the query
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    //Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Display the results
    echo "<div>";
    if (count($results) > 0) {
        foreach ($results as $user) {
            echo '<div class="result_box">';
            echo '<span class="user"><img class="bild1" src=pictures/user_icon.jpg alt="User_Icon"></span>';
            echo "<p>Username: " . $user['benutzername'] . "</p>";
            echo "<p>Name: " . $user['name'] . "</p>";
            echo "<p>Vorname: " . $user['vorname'] . "</p>";
            echo "<p>Email: " . $user['email'] . "</p>";
            echo "</div>";
        }
    } else {
        echo '<div id= "noresults">';
        echo "No results found :(";
        echo '</div>';
    }
    echo "</div>";


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
<form method="post">
    <input type="text" name="search" placeholder="Suchen" maxlength="50" required>

    <select name="filter">
        <option value="">Filtern</option>
        <option value="ID">Id</option>
        <option value="Username">Username</option>
        <option value="Name">Nachname</option>
        <option value="Vorname">Vorname</option>
        <option value="Email">Email</option>
    </select>
    <input type="submit" value="Submit">
</form>

<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>