<?php include("header.php") 
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselpakket</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <br>
<?php
require_once 'include/errortext.php';

    if (isset($_SESSION["VoorNaam"]) && isset($_SESSION["AchterNaam"])) {
        echo "<br><br>gebruiker: " . $_SESSION["VoorNaam"] . " " . $_SESSION["AchterNaam"] . $_SESSION["GebruikerId"]  ;
    } else {
        echo "No user is logged in.";
    }
    ?>    

    <div >
        <h1 >Voedselbankexamen Almere</h1><br>
        <p >Bij problemen met de pagina's, contacteer: Danny, Aman of Mika</p>
    </div>
</body>
</html>


