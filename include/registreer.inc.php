<?php
// Controleer of het registratieformulier is ingediend
if(isset($_POST["submit"])){
        // Haal de invoerwaarden op uit het registratieformulier
    $voorNaam = $_POST["VoorNaam"];
    $achterNaam = $_POST["AchterNaam"];
    $tussenvoegsels = $_POST["Tussenvoegsels"];
    $email = $_POST["email"];
    $wachtwoord = $_POST["Wachtwoord"];
    $herhaalWachtwoord = $_POST["HerhaalWachtwoord"];
    $Positie = $_POST["Positie"];

    require_once 'dbh.php';
    require_once 'functions.php';


        // Controleer of er lege invoervelden zijn
    if(emptyInputSignup($voorNaam,$achterNaam,$email,$wachtwoord,$herhaalWachtwoord) !== false){
        header("location: ../registreer.php?error=emptyinput ");
        exit();
   }

    // Controleer of de wachtwoorden overeenkomen
    if (pwdMatch($wachtwoord, $herhaalWachtwoord)!== false) {
        header("location: ../registreer.php?error=pwdsdontmatch");
        exit();
    }



    // Maak de gebruiker aan in de database
    createUser($conn,$voorNaam, $achterNaam, $tussenvoegsels ,$email , $Positie,$wachtwoord );


}
    // Controleer of het update formulier is ingediend
else if(isset($_POST["update_gebruiker"])){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        require_once 'dbh.php';
        require_once 'functions.php';

        // Haal de invoerwaarden op uit het update formulier en escapen voor veiligheid
        if (isset($_POST['update_gebruiker'])) {
            
            $gebruikerid = $conn->real_escape_string($_POST['gebruikerid']);
            $voornaam = $conn->real_escape_string($_POST['VoorNaam']);
            $AchterNaam = $conn->real_escape_string($_POST['AchterNaam']);
            $Tussenvoegsels = $conn->real_escape_string($_POST['Tussenvoegsels']);
            $email = $conn->real_escape_string($_POST['email']);
            $Positie = $conn->real_escape_string($_POST['Positie']);

                // Update query om de gebruikersgegevens bij te werken in de database
            $update_query = "UPDATE gebruikers SET voornaam='$voornaam', achternaam='$AchterNaam', tussenvoegsels='$Tussenvoegsels', email='$email', positie='$Positie' WHERE gebruikerid='$gebruikerid'";
        
            // Voer de update query uit en controleer of deze succesvol was
            if ($conn->query($update_query) === TRUE) {
                echo '<script>
                        alert("De leverancier is succesvol bijgewerkt.");
                        window.location.href = window.location.href.split("?")[0];
                      </script>';
                      header("location: ../registreer.php");
                exit();
            } else {
                echo "Fout bij het bijwerken van de leverancier: " . $conn->error;
            }
        }

    }

}
else{
        // Als geen van de formulieren zijn ingediend, ga terug naar de registratiepagina
    header("location ../registreer.php");
    exit();
}


