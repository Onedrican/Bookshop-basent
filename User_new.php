<?php
include ("includesite.php");



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
    $admin = $_POST['Admin_add'];

    // Validate the inputs
    if (strlen($benutzername) < 5 || strlen($benutzername) > 45) {
        echo "Invalid input. Please enter a string with a length between 5 and 45.";
        return;
    }
    if (strlen($name) < 5 || strlen($name) > 45) {
        echo "Invalid input. Please enter a string with a length between 5 and 45.";
        return;
    }
    if (strlen($vorname) < 5 || strlen($vorname) > 45) {
        echo "Invalid input. Please enter a string with a length between 5 and 45.";
        return;
    }
    if (strlen($password) < 5 || strlen($password) > 50) {
        echo "Invalid input. Please enter a string with a length of at least 8. ";
        return;
    }
    if (strlen($email) < 5 || strlen($email) > 45) {
        echo "Invalid input. Please enter a string with a length between 5 and 45.";
        return;
    }
    //Hashing password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    //Prepare the SQL query
    $query = "INSERT INTO benutzer (benutzername, name, vorname, passwort, email, admin, verfasser) VALUES (:katalog, :kurztitle, :kategorie, :autor, :title, :zustand, :verfasser)";

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
    <input type="text" name="Benutzername_add" placeholder="Benutzername" minlength="5" maxlength="45" required><br>
    <input type="text" name="Name_add" placeholder="Name" minlength="1" maxlength="45" required><br>
    <input type="text" name="Vorname_add" placeholder="Vorname" minlength="1" maxlength="45" required><br>
    <input type="password" name="Password_add" placeholder="Password min.8" required minlength="8" maxlength="50"><br>
    <input type="text" name="Email_add" placeholder="Email" required minlength="5" maxlength="50"><br>
    <label for="Admin">Admin?</label><input type="checkbox" name="Admin"><br>

    <input type="submit" value="Submit">
</form>
</div>

<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>