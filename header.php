<?php
 session_start();
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
                if(isset($_SESSION["VoorNaam"])){
                    echo "<a style='text-decoration: none; color: white;'href='index.php' class='header-item'><p> Home </p> </a>'";
                    echo "<a style='text-decoration: none; color: white;'href='klanten.php' class='header-item'><p> klanten </p> </a>";
                    echo "<a style='text-decoration: none; color: white;'href='leverancier.php' class='header-item'><p> Leveranciers </p> </a>";
                    echo "<a style='text-decoration: none; color: white;'href='invetaris.php' class='header-item'><p> Magazijn </p> </a>";
                    echo "<a style='text-decoration: none; color: white;'href='voedselpakket.php' class='header-item'><p> Voedselpakketen </p> </a>";
                    echo "<li><a class='linkText' href='include/Loguit.php'>Log Uit</a></li>";
                }else{
                    echo "<a style='text-decoration: none; color: white;'href='login.php' class='header-item'><p> Login </p> </a>'";
                }
                     ?>
    </header>
</body>
</html>






