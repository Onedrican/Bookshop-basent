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
    <a href="Pw_vergessen.php">Passwort vergessen?</a>
</div>

<?php
include ("includesite.php");
session_start();
echo var_dump($_SESSION);

//Connection to the database
$servername = "127.0.0.1:3306";
$username = "rundb";
$password = "runpass";
$conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['signin'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Fetch the user from the database
    $query = "SELECT * from benutzer WHERE benutzername = :username";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['passwort'])) {
            $_SESSION["is_logged_in"] = true;
            header('location: adminsite.php');
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    if ($user) {
        $_SESSION["is_logged_in"] = true;
        header('location: adminsite.php');
    } else {
        header('location: login.php');
    }
}
?>
    
</body>
</html>