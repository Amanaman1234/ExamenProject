<?php
// Controleer of het formulier voor wachtwoord wijzigen is ingediend
if(isset($_POST["submit"])){

    // Haal de invoerwaarden op uit het formulier
    $ogPWd = $_POST["OudeWachtwoord"];
    $newPwd = $_POST["NieuwWachtwoord"];
    $newPwdRep = $_POST["NieuwWachtwoordRep"];

    require_once 'dbh.php';
    require_once 'functions.php';

    // Controleer of er lege invoervelden zijn
    if(emptyInputChangePwd($ogPWd, $newPwd,$newPwdRep)){
        header("location: ../Veranderpw.php?error=emptyinput");
        exit;
    }

    // Controleer of de nieuwe wachtwoorden overeenkomen    
    if(ChangepwdMatch($newPwd, $newPwdRep)!== false){
        header("location: ../Veranderpw.php?error=pwdsnomatching");
        exit();
    }

    // Wijzig het wachtwoord in de database
    Changepwd($conn, $newPwd);

}else {
    // Als het formulier niet is ingediend, ga terug naar de wachtwoord wijzigen pagina
    header("location: ../Veranderpw.php");
    exit();
}