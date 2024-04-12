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
  <h1>Sign In</h1>
  <hr>
  <form method="post">
    <div class="mb-3">
      <label for="username">Username</label>
      <input type="text" name="username" placeholder="Enter your username" autocomplete="off" required>
    </div>
    <div>
      <label for="password" >Password</label>
      <input type="password" name="password" placeholder="Enter your password" required>
    </div>
    <div>
      <input type="submit" name="signin" value="Sign In">
    </div>
  </form>
</div>

<?php
session_start();

$servername = "127.0.0.1:3306";
$dbusername = "rundb";
$dbpassword = "runpass";


$conn = new PDO("mysql:host=$servername;dbname=books", $dbusername, $dbpassword);

if (isset($_POST['signin'])) {
    $username = htmlspecialchars(trim($_POST['username']));;
    $password = htmlspecialchars(trim($_POST['password']));;

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
    
</body>
</html>