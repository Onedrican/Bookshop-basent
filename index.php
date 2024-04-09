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
    <p>Bookshop BASENT</p>
    <div class="burgermenu">
      <div  class="burger"></div>
      <div  class="burger"></div>
      <div  class="burger"></div> 
    </div>
  </header>

        <a href="index.php">HOME</a>
        <a href="login.php">Login</a>
        <a href="searchdbTest.php">search db test</a>

    <?php
        /*//Connection to the database
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

        //Query to get all the books    
        // $sql = "SELECT * FROM buecher";
        // $result = $conn->query($sql);
        
        // if ($result->rowCount() > 0) {
        //     // output data of each row
        //     while($row = $result->fetch()) {
        //       echo "id: " . $row["id"]. " - Kurztitel: " . $row["kurztitle"]. " - Author: " . $row["autor"]. "<br>";
        //     }
        //   } else {
        //     echo "0 results";
        //   }
        //   SELECT kurztitle, autor FROM buecher WHERE kurztitle LIKE 'A%'
        //   $sql = "SELECT * FROM buecher";
        // $result = $conn->query($sql);
        $sql = "SELECT kurztitle, autor FROM buecher WHERE kurztitle LIKE 'A%'";
        $result = $conn->query($sql);
        
        if ($result->rowCount() > 0) {
            // output data of each row
            while($row = $result->fetch()) {
              echo "Kurztitle: " . $row["kurztitle"]. " - Author: " . $row["autor"]. "<br>";
            }
          } else {
            echo "0 results";
          }*/
    ?>
</body>
</html>