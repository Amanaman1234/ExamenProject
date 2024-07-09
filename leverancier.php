<?php include("header.php");
require_once 'include/functions.php';
checkaccesmedewerker();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/klanten.css">
    <title>Leveranciers</title>
</head>
<body class="background">

<h2>Nieuwe Leverancier Toevoegen</h2>
<form id="leverancierForm" method="POST" action="">
    <input type="hidden" name="leverancierid" id="leverancierid">
    <input type="text" name="bedrijfnaam" id="bedrijfnaam" placeholder="Bedrijfsnaam" required>
    <input type="text" name="adress" id="adress" placeholder="Adres" required>
    <input type="email" name="bedrijfemail" id="bedrijfemail" placeholder="Bedrijfs email" required>
    <input type="tel" name="telefoonnummer" id="telefoonnummer" placeholder="Telefoonnummer" required>
    <input type="text" name="contactpersoon" id="contactpersoon" placeholder="Contactpersoon" required>
    <input type="date" name="leveringdatum" id="leveringdatum" placeholder="Leveringdatum" required>
    <input type="date" name="volgendelevering" id="volgendelevering" placeholder="Volgende levering" required>
    <button type="submit" id="addLeverancierBtn" name="add_leverancier">Toevoegen</button>
    <button type="submit" id="updateLeverancierBtn" name="update_leverancier" style="display: none;">Bijwerken</button>
</form>

<h1>Leveranciers Overzicht</h1>
<table id="leveranciersTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>Bedrijfsnaam</th>
            <th>Adres</th>
            <th>Bedrijfs email</th>
            <th>Telefoonnummer</th>
            <th>Contactpersoon</th>
            <th>Leveringdatum</th>
            <th>Volgende levering</th>
            <th>Bewerken</th>
        </tr>
    </thead>
    <tbody>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controleer of het formulier voor het toevoegen van een leverancier is ingediend
    if (isset($_POST['add_leverancier'])) {

        // Haal de waarden op uit het formulier en ontsmet deze voor veiligheid
        $bedrijfnaam = $conn->real_escape_string($_POST['bedrijfnaam']);
        $adress = $conn->real_escape_string($_POST['adress']);
        $bedrijfemail = $conn->real_escape_string($_POST['bedrijfemail']);
        $telefoonnummer = $conn->real_escape_string($_POST['telefoonnummer']);
        $contactpersoon = $conn->real_escape_string($_POST['contactpersoon']);
        $leveringdatum = $conn->real_escape_string($_POST['leveringdatum']);
        $volgendelevering = $conn->real_escape_string($_POST['volgendelevering']);

        // Bereid de SQL-query voor om een nieuwe leverancier toe te voegen
        $insert_query = "INSERT INTO leveranciers (bedrijfnaam, adress, bedrijfemail, telefoonnummer, contactpersoon, leveringdatum, volgendelevering) VALUES ('$bedrijfnaam', '$adress', '$bedrijfemail', '$telefoonnummer', '$contactpersoon', '$leveringdatum', '$volgendelevering')";
        
        // Voer de query uit en controleer of deze succesvol is
        if ($conn->query($insert_query) === TRUE) {
            $_SESSION['success_message'] = "De leverancier is succesvol toegevoegd.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            // Als niet succesvol, geef foutmelding weer
            echo "Fout bij het toevoegen van de leverancier: " . $conn->error;
        }
    }

    // Controleer of het formulier voor het bijwerken van een leverancier is ingediend
    if (isset($_POST['update_leverancier'])) {
        $leverancierid = $conn->real_escape_string($_POST['leverancierid']);
        $bedrijfnaam = $conn->real_escape_string($_POST['bedrijfnaam']);
        $adress = $conn->real_escape_string($_POST['adress']);
        $bedrijfemail = $conn->real_escape_string($_POST['bedrijfemail']);
        $telefoonnummer = $conn->real_escape_string($_POST['telefoonnummer']);
        $contactpersoon = $conn->real_escape_string($_POST['contactpersoon']);
        $leveringdatum = $conn->real_escape_string($_POST['leveringdatum']);
        $volgendelevering = $conn->real_escape_string($_POST['volgendelevering']);

        // Bereid de SQL-query voor om een bestaande leverancier bij te werken
        $update_query = "UPDATE leveranciers SET bedrijfnaam='$bedrijfnaam', adress='$adress', bedrijfemail='$bedrijfemail', telefoonnummer='$telefoonnummer', contactpersoon='$contactpersoon', leveringdatum='$leveringdatum', volgendelevering='$volgendelevering' WHERE leverancierid='$leverancierid'";
        
        // Voer de query uit en controleer of deze succesvol is
        if ($conn->query($update_query) === TRUE) {
            // Als succesvol, sla succesboodschap op in sessie en laad de pagina opnieuw met een alert
            $_SESSION['success_message'] = "De leverancier is succesvol bijgewerkt.";
            echo '<script>
                    alert("De leverancier is succesvol bijgewerkt.");
                    window.location.href = window.location.href.split("?")[0];
                  </script>';
            exit();
        } else {
            // Als niet succesvol, geef foutmelding weer
            echo "Fout bij het bijwerken van de leverancier: " . $conn->error;
        }
    }
}

// Controleer of er een zoekterm is opgegeven in de URL en ontsmet deze voor veiligheid
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Basis SQL-query om alle leveranciers op te halen
$query = "SELECT * FROM leveranciers";

// Als er een zoekterm is opgegeven, voeg dan een WHERE-clausule toe aan de query
if (!empty($search)) {
    $query .= " WHERE bedrijfnaam LIKE '%$search%'";
}

// Voer de query uit en sla het resultaat op in $result
$result = $conn->query($query);
// Controleer of er rijen zijn opgehaald
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        // Toon de gegevens van de leverancier in tabelcellen, ontsmet om XSS-aanvallen te voorkomen
        echo "<td>" . htmlspecialchars($row['bedrijfnaam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['adress']) . "</td>";
        echo "<td>" . htmlspecialchars($row['bedrijfemail']) . "</td>";
        echo "<td>" . htmlspecialchars($row['telefoonnummer']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contactpersoon']) . "</td>";
        echo "<td>" . htmlspecialchars($row['leveringdatum']) . "</td>";
        echo "<td>" . htmlspecialchars($row['volgendelevering']) . "</td>";

        // Voeg een bewerkingslink toe die de ID van de leverancier bevat
        echo "<td><a href='?edit=" . htmlspecialchars($row['leverancierid']) . "'>Bewerken</a></td>";
        echo "</tr>";
    }
} else {
    // Als er geen gegevens zijn gevonden, toon een melding in een enkele tabelrij
    echo "<tr><td colspan='8'>Geen gegevens gevonden</td></tr>";
}
?>
    </tbody>
</table>

<?php
// Controleer of er een 'edit' parameter is opgegeven in de URL
if (isset($_GET['edit'])) {

    // Haal het leverancierid op uit de URL en ontsmet deze voor veiligheid
    $leverancierid = $conn->real_escape_string($_GET['edit']);

    // Bereid de SQL-query voor om de gegevens van de geselecteerde leverancier op te halen
    $edit_query = "SELECT * FROM leveranciers WHERE leverancierid = '$leverancierid'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {

        // Haal de gegevens van de geselecteerde leverancier op
        $edit_row = $edit_result->fetch_assoc();
?>
    <script>
        // Vul de formuliervelden met de gegevens van de geselecteerde leverancier
        document.getElementById("leverancierid").value = "<?php echo htmlspecialchars($edit_row['leverancierid']); ?>";
        document.getElementById("bedrijfnaam").value = "<?php echo htmlspecialchars($edit_row['bedrijfnaam']); ?>";
        document.getElementById("adress").value = "<?php echo htmlspecialchars($edit_row['adress']); ?>";
        document.getElementById("bedrijfemail").value = "<?php echo htmlspecialchars($edit_row['bedrijfemail']); ?>";
        document.getElementById("telefoonnummer").value = "<?php echo htmlspecialchars($edit_row['telefoonnummer']); ?>";
        document.getElementById("contactpersoon").value = "<?php echo htmlspecialchars($edit_row['contactpersoon']); ?>";
        document.getElementById("leveringdatum").value = "<?php echo htmlspecialchars($edit_row['leveringdatum']); ?>";
        document.getElementById("volgendelevering").value = "<?php echo htmlspecialchars($edit_row['volgendelevering']); ?>";

        // Verberg de 'Toevoegen' knop en toon de 'Bijwerken' knop
        document.getElementById("addLeverancierBtn").style.display = "none";
        document.getElementById("updateLeverancierBtn").style.display = "inline";
    </script>
<?php
    }
}
$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialiseer de DataTable
        $('#leveranciersTable').DataTable();

        // Verberg de 'Bijwerken' knop standaard
        $('#updateLeverancierBtn').hide();

        // Als er een 'edit' parameter in de URL staat, verberg de 'Toevoegen' knop en toon de 'Bijwerken' knop
        <?php if (isset($_GET['edit'])): ?>
        $('#addLeverancierBtn').hide();
        $('#updateLeverancierBtn').show();
        <?php endif; ?>
    });
</script>

</body>
</html>


