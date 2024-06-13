<?php

function emptyInputSignup($voorNaam,$achterNaam,$email,$wachtwoord,$repWachtwoord){
    if(empty($voorNaam) || empty($achterNaam) || empty($email)  || empty($wachtwoord) ||empty($repWachtwoord)){
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

    // echo $wachtwoord;
    // echo $pwdHashed;

    $checkPwd = password_verify($wachtwoord, $pwdHashed);

    if($checkPwd){
        session_start();
        $_SESSION["VoorNaam"] = $uidExists["voornaam"];
        $_SESSION["AchterNaam"] = $uidExists["achternaam"];
        header("location: ../index.php");
        exit();

    }else {
        header("location: ../login.php?error=wrongloginp");
        exit();
    }

    exit();
}

