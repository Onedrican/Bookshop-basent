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

        <a href="index.php">HOME</a>
        <a href="login.php">Login</a>
        <a href="searchdbTest.php">search db test</a>



    <?php
 //Connection to the database
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $search = htmlspecialchars(trim($_POST['search']));
     $sort = $_POST['sort'];
     $filter = $_POST['filter'];
 
     // Connect to the database
     $servername = "127.0.0.1:3306";
     $username = "rundb";
     $password = "runpass";
     $conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
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
     $stmt->execute(['search' => "%$search%"]);
 
     // Fetch the results
     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
     // Display the results
     foreach ($results as $book) {
         echo $book['kurztitle'] . " by " . $book['autor'] . " (Number: " . $book['nummer'] . ")<br>";
     }
 }
    ?>


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
<label for="bps">Vie viele </label>

<span id="book1"><img class="bild1" src=blaues_Buch.jpg></span>
<span id="book2"></span>
<span id="book3"></span>
<span id="book4"></span>
<span id="book5"></span>
<span id="book6"></span>
<span id="book7"></span>
<span id="book8"></span></br>
<span id="book9"></span>
<span id="book10"></span>
<span id="book11"></span>
<span id="book12"></span>
<span id="book13"></span>
<span id="book14"></span>
<span id="book15"></span>
<span id="book16"></span>

<select onChange="if (this.value) window.location.href=this.value" id="bps">
    <option selected="selected" value="twelve">12 Bücher pro Seite</option>
        <option value= "searchdbTest.php">18 Bücher pro Seite</option>
</select>

</body>
</html>