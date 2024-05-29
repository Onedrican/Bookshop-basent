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
<div>
  <p id="title">Sign In</p>
  <hr>
  <form method="post" name="loginpost">
    <div class="mb-3">
      <br>
      <label for="username">Username</label><br>
      <input type="text" name="username" placeholder="Enter your username" autocomplete="off" required>
      <br>
      <br>
    </div>
    <div>
      <label for="password" >Password</label><br>
      <input type="password" name="password" placeholder="Enter your password" required>
      <br>
      <br>
    </div>
    <div>
      <input type="submit" name="signin" value="Sign In">
    </div>
  </form>
</div>

<?php
//include ("includesite.php");
session_start();
error_reporting(E_ERROR | E_PARSE);

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