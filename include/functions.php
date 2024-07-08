<?php

// Functie om te controleren of de invoervelden voor registratie leeg zijn
function emptyInputSignup($voorNaam, $achterNaam, $email, $wachtwoord, $repWachtwoord) {
    if(empty($voorNaam) || empty($achterNaam) || empty($email) || empty($wachtwoord) || empty($repWachtwoord)) {
        return true;
    } else {
        return false;
    }
}

// Functie om te controleren of de invoervelden voor het wijzigen van wachtwoord leeg zijn
function emptyInputChangePwd($ogPWd, $newPwd, $newPwdRep) {
    if(empty($ogPWd) || empty($newPwd) || empty($newPwdRep)) {
        return true;
    } else {
        return false;
    }
}

// Functie om te controleren of de invoervelden voor inloggen leeg zijn
function emptyInputLogin($gebruikersnaam, $wachtwoord) {
    if(empty($gebruikersnaam) || empty($wachtwoord)) {
        return true;
    } else {
        return false;
    }
}

// Functie om te controleren of de wachtwoorden overeenkomen tijdens registratie
function pwdMatch($wachtwoord, $herhaalWachtwoord) {
    if($wachtwoord !==  $herhaalWachtwoord) {
        return true;
    } else {
        return false;
    }
}

// Functie om te controleren of de nieuwe wachtwoorden overeenkomen bij het wijzigen van wachtwoord
function ChangepwdMatch($newPwd, $newPwdRep) {
    if($newPwd !==  $newPwdRep) {
        return true;
    } else {
        return false;
    }
}

// Functie om te controleren of een gebruiker al bestaat op basis van e-mail
function gebrExists($conn, $email) {
    $sql = "SELECT * FROM gebruikers WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../registreer.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    return mysqli_fetch_assoc($resultData);
}

// Functie om een nieuwe gebruiker aan te maken
function createUser($conn, $voorNaam, $achterNaam, $tussenvoegsels, $email, $Positie, $wachtwoord) {
    $sql = "INSERT INTO gebruikers (voornaam, achternaam, tussenvoegsels, email, positie, wachtwoord) VALUES (?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../registreer.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($wachtwoord, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssssss", $voorNaam, $achterNaam, $tussenvoegsels, $email, $Positie, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location:../registreer.php?error=none");
}

// Functie om een gebruiker in te loggen
function loginUser($conn, $email, $wachtwoord) {
    $uidExists = gebrExists($conn, $email);

    if(!$uidExists) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["wachtwoord"];
    $checkPwd = password_verify($wachtwoord, $pwdHashed);

    if($checkPwd) {
        session_start();
        $_SESSION["VoorNaam"] = $uidExists["voornaam"];
        $_SESSION["AchterNaam"] = $uidExists["achternaam"];
        $_SESSION["Positie"] = $uidExists["positie"];
        $_SESSION["GebruikerId"] = $uidExists["gebruikerid"];

        header("location: ../index.php?error=none");
        exit();
    } else {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    exit();
}

// Functie om de headerinhoud dynamisch aan te passen op basis van de positie van de gebruiker
function headerInhoud() {
    if(isset($_SESSION["Positie"])) {
        $positione = $_SESSION["Positie"];
        
        if ($positione == "medewerker") {
            echo "<a style='text-decoration: none; color: white;' href='leverancier.php' class='header-item'><p> Leveranciers </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='invetaris.php' class='header-item'><p> Magazijn </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='Veranderpw.php' class='header-item'><p> Verander Wachtwoord </p></a>";
            echo "<ul><a class='linkText' href='include/Loguit.php'>Loguit</a></ul>";
        } else if($positione == "vrijwilliger") {
            echo "<a style='text-decoration: none; color: white;' href='voedselpakket.php' class='header-item'><p> Voedselpakketen </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='Veranderpw.php' class='header-item'><p> Verander Wachtwoord </p></a>";
            echo "<ul><a class='linkText' href='include/Loguit.php'>Loguit</a></ul>";
        } else if($positione == "directie") {
            echo "<a style='text-decoration: none; color: white;' href='klanten.php' class='header-item'><p> klanten </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='leverancier.php' class='header-item'><p> Leveranciers </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='invetaris.php' class='header-item'><p> Magazijn </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='voedselpakket.php' class='header-item'><p> Voedselpakketen </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='registreer.php' class='header-item'><p> Registreer </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='Veranderpw.php' class='header-item'><p> Verander Wachtwoord </p></a>";
            echo "<a style='text-decoration: none; color: white;' href='overzicht.php' class='header-item'><p>Maandelijkse overzicht</p></a>";
            echo "<ul><a class='linkText' href='include/Loguit.php'>Loguit</a></ul>";
        }
    } else {
        echo "<a style='text-decoration: none; color: white;' href='login.php' class='header-item'><p> Login </p></a>";
    }
}

// Functie om het wachtwoord van een gebruiker te wijzigen
function Changepwd($conn, $newPwd) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if(!isset($_SESSION["GebruikerId"])) {
        header("location: ../login.php?error=notloggedin");
        exit();
    }

    $id = $_SESSION["GebruikerId"];

    $sql = "UPDATE gebruikers SET wachtwoord = ? WHERE gebruikerid = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../login.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../index.php?Succ6:)");
    exit();
}

// Functie om de huidige tijd en een bijbehorende groet weer te geven
function curTime()  {
    date_default_timezone_set('Europe/Amsterdam');

    $klok = date ("h");
    $AmorPm = date("A");

    if($klok <= 12 && $AmorPm == "AM") {
        echo "goedemorgen";
    } else if ($klok <= 6 && $klok >= 0 && $AmorPm == "PM") {
        echo "Goede middag";
    } else if($klok <= 12 && $AmorPm == "PM") {
        echo "Goeden avond";
    }
}

// Functie om te controleren of een gebruiker de rol 'directie' heeft
function checkaccesdirectie() {
    $positione = $_SESSION["Positie"];
    if($positione != "directie") {
        header("location: ../ExamenProject/index.php?error=rotop");
    }
}

// Functie om te controleren of een gebruiker de rol 'medewerker' heeft
function checkaccesmedewerker() {
    $positione = $_SESSION["Positie"];
    if($positione == "vrijwilliger") {
        header("location: ../ExamenProject/index.php?error=rotop");
    }
}

// Functie om te controleren of een gebruiker de rol 'vrijwilliger' heeft
function checkaccesvrijwilliger() {
    $positione = $_SESSION["Positie"];
    if($positione == "medewerker") {
        header("location: ../ExamenProject/index.php?error=rotop");
    }
}

