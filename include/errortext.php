<?php
if(isset($_GET["error"])){
    switch($_GET["error"]){
        case "emptyinput":
            echo "<p>full iets in pls >_<</p>";
            break;
        case "invalidusername":
            echo "<p>gebruik normale username</p>";
            break;
        case "pwdsdontmatch":
            echo "<p>wachtwoord is niet hetzelfde</p>";
            break;
        case "gebruikersnaamgebruikt":
            echo "<p>kies andere gebruikersnaam</p>";
            break;
        case "wronglogin":
            echo "<p>Verkeerde Wachtwoord</p>";
            break;
        case "stmtfailed":
            echo "<p>er ging wat fout :( probeer opnieuw</p>";
            break;
        case "none":
            echo "je bent ingelogd :)";
            break;
        case "rotop":
            echo "je hebt geen toegan tot deze website";
            break;
        case "invalidusername";
            echo "Gebruik een normaale email";
            break;
        default:
            echo "<p>Onbekende fout</p>";
            break;
    }
}
?>
