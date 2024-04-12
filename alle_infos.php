<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stlye.css">
    <title>Document</title>
</head>
<body>
<?php
if (!isset($_GET['id'])) {
    $_GET['id'] = 1;
}
$servername = "127.0.0.1:3306";
$username = "rundb";
$password = "runpass";
$conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare the SQL query
$query = "SELECT * FROM buecher WHERE id = :id";
// Execute the query
$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();
// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$book = $results[0];

echo '<span class="frontpage_books">';
echo '<a href="alle_infos.php"><span class="book1"><img class="bild1" src=pictures/book.webp alt="bookcover"></span></a>';
echo "<h2>" . $book['kurztitle'] . "</h2>";
echo "<p>Title: " . $book['title'] . "</p>";
echo "<p>Author: " . $book['autor'] . "</p>";
echo "<p>Katalog: " . $book['katalog'] . "</p>";
echo "<p>Kategorie: " . $book['kategorie'] . "</p>";
echo "<p>Zustand: " . $book['zustand'] . "</p>";
echo "</span>";

?>
</body>
</html>
