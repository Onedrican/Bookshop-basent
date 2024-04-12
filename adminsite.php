<?php
session_start();

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
    session_destroy();

    // Redirect to login 
    header('Location: login.php');
    exit;
}
//Mysql Connection
$servername = "127.0.0.1:3306";
$dbusername = "rundb";
$dbpassword = "runpass";

$conn = new PDO("mysql:host=$servername;dbname=books", $dbusername, $dbpassword);

//user authentication
if (isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * from admin WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $username, 'password' => $password]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['name'] = $user['username'];
        $_SESSION['username'] = $user['username'];
        header('location: adminsite.php');
    } else {
        header('location: login.php');
    }
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
<div class="container col-12 border rounded mt-3">
  <h1 class=" mt-3 text-center">Welcome, This your dashboard!! </h1>
  <form method="post">
    <button type="submit" name='signout' > Sign Out</button>
  </form>
</div>
</body>
</html>