<?php
include ("includesite.php");
include ("Include/Dbconnection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $search = htmlspecialchars(trim($_POST['search']));
        $filter = $_POST['filter'];

        //Preparing beginning of querry
        $search = $search .= "%";
        $query = "SELECT * FROM benutzer WHERE";

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
                $query .= "name LIKE :search";
                break;
        }

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        $stmt->execute();

        //Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
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

<form method="post">
    <input type="text" name="search" placeholder="Suchen" maxlength="50">

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

<form method="post">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>