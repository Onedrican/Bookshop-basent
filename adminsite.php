<?php
session_start();

if (isset($_POST['signout'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    // Redirect to login page
    header('Location: login.php');
    exit;
}

$servername = "127.0.0.1:3306";
$dbusername = "rundb";
$dbpassword = "runpass";

$conn = new PDO("mysql:host=$servername;dbname=books", $dbusername, $dbpassword);

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
    <title>Document</title>
</head>
<body>
<div class="container col-12 border rounded mt-3">
  <h1 class=" mt-3 text-center">Welcome, This your dashboard!! </h1>
  <form action="" method="post">
    <button type="submit" name='signout' > Sign Out</button>
  </form>
</div>
</body>
</html>