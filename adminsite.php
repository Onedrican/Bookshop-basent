<?php

session_start();
if (!isset($_SESSION["is_logged_in"]) || $_SESSION["is_logged_in"] === false) {
    header('location: login.php');
    die();
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
<div class="bookwahl">
        <div  class= booksh>Bücher</div> 
        <div class="dropdown1">
        <div class="dropdown-content">
        <a href="Book_delete.php">Bücher löschen</a> <br>
        <a href="Book_change.php">Bücher ändern</a> <br>
        <a href="Book_add.php">Bücher hinzufügen</a> <br>
        </div>  
        </div>
    </div>
<h1>Book shit</h1>
<a href="Book_delete.php">Bücher löschen</a> <br>
<a href="Book_change.php">Bücher ändern</a> <br>
<a href="Book_add.php">Bücher hinzufügen</a> <br>
<br>
<h1>User shit</h1>
<a href="User_search.php">User suchen</a> <br>
<a href="User_delete.php">User löschen</a> <br>
<a href="User_change.php">User ändern</a> <br>
<a href="User_new.php">Neuer User</a> <br>


    <form method="post">
    <button type="submit" name='signout' > Sign Out</button>
    </form>

</body>
</html>