<?php



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
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<form method="get">
    <button type="submit" name='signout' > Sign Out</button>
</form>
</body>
</html>