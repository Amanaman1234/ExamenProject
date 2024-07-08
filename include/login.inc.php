<?php
// Controleer of het loginformulier is ingediend
if(isset($_POST["submit"])){

    // Haal de invoerwaarden op uit het loginformulier
    $email = $_POST["email"];
    $wachtwoord = $_POST["Wachtwoord"];

    require_once 'dbh.php';
    require_once 'functions.php';

    // Controleer of er lege invoervelden zijn
    if(emptyInputLogin($email,$wachtwoord)){
        header("location: ../login.php?error=emptyinput");
        exit;
    }

    // Log de gebruiker in
   loginUser($conn, $email, $wachtwoord);


}
else{
    // Als het formulier niet is ingediend, ga terug naar de loginpagina
    header("location: ../login.php");
    exit();
}
