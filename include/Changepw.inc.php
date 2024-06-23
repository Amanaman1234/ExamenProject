<?php

if(isset($_POST["submit"])){

    $ogPWd = $_POST["OudeWachtwoord"];
    $newPwd = $_POST["NieuwWachtwoord"];
    $newPwdRep = $_POST["NieuwWachtwoordRep"];

    require_once 'dbh.php';
    require_once 'functions.php';


    Changepwd($conn, $newPwd);


}
else{
    header("location: ../login.php");
    exit();
}