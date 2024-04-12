<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
echo '<span class="frontpage_books">';
echo '<a href="alle_infos.php"><span class="book1"><img class="bild1" src=pictures/book.webp alt="bookcover"></span></a>';
echo "<h2>" . $book['kurztitle'] . "</h2>";
echo "<p>Author: " . $book['autor'] . "</p>";
echo "<p>Kategorie: " . $book['kategorie'] . "</p>";
echo "</span>";
?>
</body>
</html>
