<?php

function emptyInputSignup($voorNaam, $achterNaam, $email, $wachtwoord, $herhaalWachtwoord) {
    if (empty($voorNaam) || empty($achterNaam) || empty($email) || empty($wachtwoord) || empty($herhaalWachtwoord)) {
        return true;
    } else {
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

if($row = mysqli_fetch_assoc($resultData)){
    return $row;
}else{
    $result = false;
    return $result;
}

mysqli_stmt_close($stmt);
}

function createUser($conn,$voorNaam, $achterNaam, $tussenvoegsels ,$email , $wachtwoord ){
    $sql = "INSERT INTO gebruikers (voornaam, achternaam,tussenvoegsels, email, wachtwoord) VALUES (?,?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
       header("location: ../registreer.php?error=stmtfailed");
       exit();
    }

$hashedPwd = password_hash($wachtwoord, PASSWORD_DEFAULT);

mysqli_stmt_bind_param($stmt, "sssss", $voorNaam, $achterNaam, $tussenvoegsels, $email , $wachtwoord );
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("location:../registreer.php?error=none");
}

