<?php
if(isset($_POST["submit"])){
    $voorNaam = $_POST["VoorNaam"];
    $achterNaam = $_POST["AchterNaam"];
    $tussenvoegsels = $_POST["Tussenvoegsels"];
    $email = $_POST["email"];
    $wachtwoord = $_POST["Wachtwoord"];
    $herhaalWachtwoord = $_POST["HerhaalWachtwoord"];
    $Positie = $_POST["Positie"];


    require_once 'dbh.php';
    require_once 'functions.php';

    if(emptyInputSignup($voorNaam,$achterNaam,$email,$wachtwoord,$herhaalWachtwoord) !== false){
        header("location: ../registreer.php?error=emptyinput ");
        exit();
   }


    if (pwdMatch($wachtwoord, $herhaalWachtwoord)!== false) {
        header("location: ../registreer.php?error=pwdsdontmatch");
        exit();
    }




    createUser($conn,$voorNaam, $achterNaam, $tussenvoegsels ,$email , $Positie,$wachtwoord );


}else if(isset($_POST["update_gebruiker"])){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        require_once 'dbh.php';
        require_once 'functions.php';

        if (isset($_POST['update_gebruiker'])) {
            $gebruikerid = $conn->real_escape_string($_POST['gebruikerid']);
            $voornaam = $conn->real_escape_string($_POST['VoorNaam']);
            $AchterNaam = $conn->real_escape_string($_POST['AchterNaam']);
            $Tussenvoegsels = $conn->real_escape_string($_POST['Tussenvoegsels']);
            $email = $conn->real_escape_string($_POST['email']);
            $Positie = $conn->real_escape_string($_POST['Positie']);
    
            $update_query = "UPDATE gebruikers SET voornaam='$voornaam', achternaam='$AchterNaam', tussenvoegsels='$Tussenvoegsels', email='$email', positie='$Positie' WHERE gebruikerid='$gebruikerid'";
        
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
    header("location ../registreer.php");
    exit();
}


