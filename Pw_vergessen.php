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
<form method="post">

    <input type="text" name="username" placeholder="Benutzername"  minlength="5" maxlength="45" required>
    <input type="password" name="oldPw" placeholder="Altes Passwort min.8" minlength="8" maxlength="50" required>
    <input type="password" name="newPw" placeholder="Neues Passwort min.8" minlength="8" maxlength="50" required>
    <input type="submit" name="submit" value="Submit">

</form>
</div>

<?php
include ("includesite.php");

//error_reporting(E_ERROR | E_PARSE);

//Connection to the database
$servername = "127.0.0.1:3306";
$username = "rundb";
$password = "runpass";
$conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (isset($_POST['submit'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $oldPw = htmlspecialchars(trim($_POST['oldPw']));
    $newPw = htmlspecialchars(trim($_POST['newPw']));

    // Validate the inputs
    if (strlen($username) < 5 || strlen($username) > 45) {
        echo "Invalid input. Please enter a string with a length between 5 and 45.";
        return;
    }
    if (strlen($oldPw) < 8 || strlen($oldPw) > 50) {
        echo "Invalid input. Please enter a string with a length between 5 and 50.";
        return;
    }
    if (strlen($newPw) < 8 || strlen($newPw) > 50) {
        echo "Invalid input. Please enter a string with a length between 5 and 50.";
        return;
    }

    // Fetch the current password for the user from the database
    $stmt = $conn->prepare("SELECT passwort FROM benutzer WHERE benutzername = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user = htmlspecialchars_decode($user['passwort']);
    if ($user) {
        // Verify old password with the password in the database
        if (password_verify($oldPw, $user)) {
            $newPwHashed = password_hash($newPw, PASSWORD_DEFAULT);

            // Update the password
            $stmt = $conn->prepare("UPDATE benutzer SET passwort = :newPw WHERE benutzername = :username");
            $stmt->execute(['newPw' => $newPwHashed, 'username' => $username]);

            echo "Password updated successfully.";
        } else {
            echo "Old password is incorrect.";
        }
    } else {
        echo "User not found.";
    }
}
?>

</body>
</html>