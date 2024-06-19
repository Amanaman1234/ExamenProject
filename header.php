<?php
 session_start();
 require_once 'include/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/header.css">
</head>
<body>
<header class="flex-Header">
        <div class="header-item logo-container">
            <a href="/ExamenProject/index.php" class="home-knop"> <img src="img/voedselbank-velsen11.png" class="logo"> 
           <div class="logo-text"><p> Voedselbank ExamenProject <br> Almere </p> </div>  </a>
           
        </div>
        <?php
        headerInhoud();
        ?>
                     
    </header>
</body>
</html>






