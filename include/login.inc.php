<?php

if(isset($_POST["submit"])){

    $email = $_POST["email"];
    $wachtwoord = $_POST["Wachtwoord"];

    require_once 'dbh.php';
    require_once 'functions.php';

    if(emptyInputLogin($email,$wachtwoord)){
        header("location: ../login.php?error=emptyinput");
        exit;
    }
    if(gebrExists($conn, $email) == false){
        header("location: ../registreer.php?error=emailingebruik");
        exit();
    }



   loginUser($conn, $email, $wachtwoord);


}
else{
    header("location: ../login.php");
    exit();
}
