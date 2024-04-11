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
        <div class="dropdown-content">
        <a href="index.php">Home</a></br>
        <a href="#">Über Uns</a></br>
        <a href="#">Rechtliches</a></br>
        <a href="login.php">Admin Login</a>
        </div>
    </div>
  </header>
        <form action="" method="post">
        <input type="text" name="search" placeholder="Suchen">
        <select name="sort">
            <option value="">Sortieren</option>
            <option value="kurztitle_asc">Name A-Z</option>
            <option value="kurztitle_desc">Name Z-A</option>
            <option value="autor_asc">Author A-Z</option>
            <option value="autor_desc">Author Z-A</option>
            <option value="nummer_asc">Nummer Aufsteigend</option>
            <option value="nummer_desc">Number Absteigend</option>
        </select>
        <select name="filter">
        <option value="">Filtern</option>
        <option value="autor">Author</option>
        <option value="title">Title</option>
        <option value="kategorie">Kategorie</option>
        <option value="kurztitle">Kurztitle</option>
    </select>
        <input type="submit" value="Submit">
    </form>



<?php
    //Connection to the database
    $servername = "127.0.0.1:3306";
    $username = "rundb";
    $password = "runpass";
    $conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Connection to the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = htmlspecialchars(trim($_POST['search']));
    $sort = $_POST['sort'];
    $filter = $_POST['filter'];

    // Prepare the SQL query
    $query = "SELECT * FROM buecher WHERE ";

    // Determine the filter
    switch ($filter) {
        case 'autor':
            $query .= "autor LIKE :search";
            break;
        case 'title':
            $query .= "title LIKE :search";
            break;
        case 'kategorie':
            $query .= "kategorie LIKE :search";
            break;
        case 'kurztitle':
            $query .= "kurztitle LIKE :search";
            break;
        default:
            $query .= "autor LIKE :search OR title LIKE :search OR kategorie LIKE :search OR kurztitle LIKE :search";
            break;
    }

    // Determine the sort order
    switch ($sort) {
        case 'kurztitle_asc':
            $query .= " ORDER BY kurztitle ASC";
            break;
        case 'kurztitle_desc':
            $query .= " ORDER BY kurztitle DESC";
            break;
        case 'autor_asc':
            $query .= " ORDER BY autor ASC";
            break;
        case 'autor_desc':
            $query .= " ORDER BY autor DESC";
            break;
        case 'nummer_asc':
            $query .= " ORDER BY nummer ASC";
            break;
        case 'nummer_desc':
            $query .= " ORDER BY nummer DESC";
            break;
    }

     // Execute the query
     $stmt = $conn->prepare($query);
     $stmt->execute(['search' => "%$search"]);
 
     // Fetch the results
     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
     // Display the results
     if (count($results) > 0) {
        foreach ($results as $book) {
            echo '<div class="result_box">';
            $kurztitle = substr($book['kurztitle'], 0, 20); // Limit the output to the first 20 characters
            echo "<h2>" . $kurztitle . "</h2>";
            echo "<p>Author: " . $book['autor'] . "</p>";
            $randomPic = rand(1,3);
            if ($randomPic == 1) {
                echo '<span id="book1"><img class="bild1" src=pictures/blaues_buch.jpg></span>';
            }
            elseif ($randomPic == 2) {
                echo '<span id="book2"><img class="bild2" src=pictures/gelbes_buch.jpg></span>';
            }
            else {
                echo '<span id="book3"><img class="bild3" src=pictures/rotes_buch.jpg></span>';
            }
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }
}
    //Display 12 Books 
    // Prepare the SQL query
    $query = "SELECT kurztitle, autor, kategorie FROM buecher WHERE autor IS NOT NULL AND autor != '' LIMIT 12";

    // Execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the results
    foreach ($results as $book) {
        echo '<div class="frontpage_books">';
        echo "<h2>" . $book['kurztitle'] . "</h2>";
        echo "<p>Author: " . $book['autor'] . "</p>";
        echo "<p>Kategorie: " . $book['kategorie'] . "</p>";
        echo "</div>";
    }
?>
</body>
</html>