<?php

function emptyInputSignup($voorNaam,$achterNaam,$email,$wachtwoord,$repWachtwoord){
    if(empty($voorNaam) || empty($achterNaam) || empty($email)  || empty($wachtwoord) ||empty($repWachtwoord)){
        return true;
    }
    else{
        return false;
    }
}
function emptyInputChangePwd($ogPWd, $newPwd,$newPwdRep){
    if(empty($ogPWd) || empty($newPwd) || empty($newPwdRep)){
        return true;
    }
    else{
        return false;
    }
}
function emptyInputLogin($gebruikersnaam,$wachtwoord){
    if(empty($gebruikersnaam) || empty($wachtwoord)){
        return true;
    }
    else{
        return false;
    }
}



function pwdMatch($wachtwoord, $herhaalWachtwoord){
    if($wachtwoord !==  $herhaalWachtwoord){
        return true;
    }else{
        return false;
    }
}

function gebrExists($conn,$email){
    $sql = "SELECT * FROM gebruikers WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
       header("location: ../registreer.php?error=stmtfailed");
       exit();
    }

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

return mysqli_fetch_assoc($resultData);

}

function createUser($conn,$voorNaam, $achterNaam, $tussenvoegsels ,$email ,$Positie ,$wachtwoord,){
    $sql = "INSERT INTO gebruikers (voornaam, achternaam,tussenvoegsels, email, positie ,wachtwoord) VALUES (?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
       header("location: ../registreer.php?error=stmtfailed");
       exit();
    }

$hashedPwd = password_hash($wachtwoord, PASSWORD_DEFAULT);

mysqli_stmt_bind_param($stmt, "ssssss", $voorNaam, $achterNaam, $tussenvoegsels, $email ,$Positie, $hashedPwd);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("location:../registreer.php?error=none");
}   

function loginUser($conn, $email, $wachtwoord){
    $uidExists = gebrExists($conn, $email);

    if(!$uidExists){
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["wachtwoord"];
    $checkPwd = password_verify($wachtwoord, $pwdHashed);

    if($checkPwd){
        session_start();
        $_SESSION["VoorNaam"] = $uidExists["voornaam"];
        $_SESSION["AchterNaam"] = $uidExists["achternaam"];
        $_SESSION["Positie"] = $uidExists["positie"];
        $_SESSION["GebruikerId"] = $uidExists["gebruikerid"];

        header("location: ../index.php");
        exit();

    }else {
        header("location: ../login.php?error=wrongloginp");
        exit();
    }

    exit();
}

function headerInhoud(){
    if(isset($_SESSION["Positie"])){

        $positione = $_SESSION["Positie"];
        
        if ($positione == "medewerker") {
            echo "<a style='text-decoration: none; color: white;'href='leverancier.php' class='header-item'><p> Leveranciers </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='invetaris.php' class='header-item'><p> Magazijn </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='voedselpakket.php' class='header-item'><p> Voedselpakketen </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='Veranderpw.php' class='header-item'><p> Verander Wachtwoord </p> </a>";
            echo "<li><a class='linkText' href='include/Loguit.php'>Log Uit</a></li>";  
    
        }else if($positione == "Vrijwilliger") {
            echo "<a style='text-decoration: none; color: white;'href='invetaris.php' class='header-item'><p> Magazijn </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='voedselpakket.php' class='header-item'><p> Voedselpakketen </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='Veranderpw.php' class='header-item'><p> Verander Wachtwoord </p> </a>";
            echo "<li><a class='linkText' href='include/Loguit.php'>Log Uit</a></li>";  
        }else if( $positione == "directie") {
            echo "<a style='text-decoration: none; color: white;'href='klanten.php' class='header-item'><p> klanten </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='leverancier.php' class='header-item'><p> Leveranciers </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='invetaris.php' class='header-item'><p> Magazijn </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='voedselpakket.php' class='header-item'><p> Voedselpakketen </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='registreer.php' class='header-item'><p> Registreer </p> </a>";
            echo "<a style='text-decoration: none; color: white;'href='Veranderpw.php' class='header-item'><p> Verander Wachtwoord </p> </a>";
            echo "<li><a class='linkText' href='include/Loguit.php'>Log Uit</a></li>";  
        }

    }
    else {
        echo "<a style='text-decoration: none; color: white;'href='login.php' class='header-item'><p> Login </p> </a>";
    
    }    
}

function Changepwd($conn, $newPwd) {
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if(!isset($_SESSION["GebruikerId"])) {
        header("location: ../login.php?error=notloggedin");
        exit();
    }

    // Get user ID from session
    $id = $_SESSION["GebruikerId"];

    // Prepare SQL statement
    $sql = "UPDATE gebruikers SET wachtwoord = ? WHERE gebruikerid = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../login.php?error=stmtfailed");
        exit();
    }

    // Hash the new password
    $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);

    // Bind parameters and execute statement
    mysqli_stmt_bind_param($stmt, "si", $hashedPwd, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect to index page
    header("location: ../index.php?passwordchange=success");
    exit();
}
