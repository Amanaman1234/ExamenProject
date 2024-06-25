<?php

if(isset($_POST["submit"])){

    $ogPWd = $_POST["OudeWachtwoord"];
    $newPwd = $_POST["NieuwWachtwoord"];
    $newPwdRep = $_POST["NieuwWachtwoordRep"];

    require_once 'dbh.php';
    require_once 'functions.php';

    if(emptyInputChangePwd($ogPWd, $newPwd,$newPwdRep)){
        header("location: ../Veranderpw.php?error=emptyinput");
        exit;
    }
    if(ChangepwdMatch($newPwd, $newPwdRep)!== false){
        header("location: ../Veranderpw.php?error=pwdsnomatching");
        exit();
    }
    
    Changepwd($conn, $newPwd);


}