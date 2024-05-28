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