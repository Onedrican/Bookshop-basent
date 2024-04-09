<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    
        <a href="index.php">HOME</a>
        <a href="login.php">Login</a>
        <a href="searchdbTest.php">search db test</a>

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
        try {
            $conn = new PDO("mysql:host=$servername;dbname=books", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

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
</body>
</html>