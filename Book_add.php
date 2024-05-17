<?php
include ("includesite.php");
//Connection to the database

$servername = "127.0.0.1:3306";
$username = "rundb";
$password = "runpass";
$conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $katalog = htmlspecialchars(trim($_POST['Katalog_add']));
    $kurztitle = htmlspecialchars(trim($_POST['Kurztitle_add']));
    $kategorie = htmlspecialchars(trim($_POST['Kategorie_add']));
    $autor = htmlspecialchars(trim($_POST['Autor_add']));
    $title = htmlspecialchars(trim($_POST['Title_add']));
    $zustand = htmlspecialchars(trim($_POST['Zustand_add']));
    $verfasser = htmlspecialchars(trim($_POST['Verfasser_add']));

    // Validate the inputs
    if (!is_numeric($katalog) || $katalog < 10 || $katalog > 19) {
        echo "Invalid Katalog input. Please enter a number between 10 and 19.";
        return;
    }

    if (strlen($kurztitle) > 100) {
        echo "Invalid Kurztitle input. Please enter a string with a maximum length of 100 characters.";
        return;
    }

    if (!is_numeric($kategorie) || $kategorie < 1 || $kategorie > 14) {
        echo "Invalid Kategorie input. Please enter a number between 1 and 14.";
        return;
    }

    if (strlen($autor) < 1 || strlen($autor) > 50) {
        echo "Invalid Autor input. Please enter a string with a length between 1 and 50.";
        return;
    }

    if (strlen($title) < 1 || strlen($title) > 50) {
        echo "Invalid Title input. Please enter a string with a length between 1 and 50.";
        return;
    }

    if (!in_array($zustand, ['M', 'S', 'G'])) {
        echo "Invalid Zustand input. Please enter either 'M', 'S', or 'G'.";
        return;
    }

    if (!is_numeric($verfasser) || $verfasser < 1 || $verfasser > 6) {
        echo "Invalid Verfasser input. Please enter a number between 1 and 6.";
        return;
    }

    // Prepare the SQL query
    $query = "INSERT INTO buecher (katalog, kurztitle, kategorie, autor, title, zustand, verfasser) VALUES (:katalog, :kurztitle, :kategorie, :autor, :title, :zustand, :verfasser)";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':katalog', $katalog);
    $stmt->bindParam(':kurztitle', $kurztitle);
    $stmt->bindParam(':kategorie', $kategorie);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':zustand', $zustand);
    $stmt->bindParam(':verfasser', $verfasser);

    // Execute the query
    $stmt->execute();

}

if (isset($_POST['signout'])) {
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
<div>
<form method="post">
    <input type="number" name="Katalog_add" placeholder="Katalog" min="10" max="19" required><br>
    <input type="text" name="Kurztitle_add" placeholder="Kurztitle" maxlength="100" required><br>
    <input type="number" name="Kategorie_add" placeholder="Kategorie 1-14" min="1" max="14" required><br>
    <input type="text" name="Autor_add" placeholder="Autor" required><br>
    <input type="text" name="Title_add" placeholder="Title" required><br>
    <input type="text" name="Zustand_add" placeholder="Zustand M/S/G" pattern="[MSG]" required><br>
    <input type="number" name="Verfasser_add" placeholder="Verfasser 1-6" min="1" max="6" required><br>

    <input type="submit" value="Submit">
</form>
</div>
<form method="post">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>