<?php
if(isset($_POST["submit"])){
    $voorNaam = $_POST["VoorNaam"];
    $achterNaam = $_POST["AchterNaam"];
    $tussenvoegsels = $_POST["Tussenvoegsels"];
    $email = $_POST["email"];
    $wachtwoord = $_POST["Wachtwoord"];
    $herhaalWachtwoord = $_POST["HerhaalWachtwoord"];


    require_once 'dbh.php';
    require_once 'functions.php';

    if(emptyInputSignup($voorNaam,$achterNaam,$tussenvoegsels,$email,$wachtwoord,$herhaalWachtwoord) !== false){
        header("location: ../registreer.php?error=emptyinput ");
        exit();
   }


    if (pwdMatch($wachtwoord, $herhaalWachtwoord)!== false) {
        header("location: ../registreer.php?error=pwdsdontmatch");
        exit();
    }

    if(gebrExists($conn, $email) !== false){
        header("location: ../registreer.php?error=gebruikersnaamgebruikt");
        exit();
    }

    createUser($conn,$voorNaam, $achterNaam, $tussenvoegsels ,$email , $wachtwoord );


}
else{
    header("location ../registreer.php");
    exit();
}

