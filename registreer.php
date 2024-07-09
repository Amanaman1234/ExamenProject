<?php include("header.php") ;
require_once 'include/functions.php';
checkaccesdirectie();
?>
<link rel="stylesheet" href="css/registreer.css">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreer</title>
</head>
<body> 
    <main class="main">
        <section class="form">

            <h1>Registreer hier een medewerker:</h1>

            <form action="include/registreer.inc.php" method="post">

                <input type="hidden" name="gebruikerid" id="gebruikerid">

                <input type="text" name="VoorNaam" id="VoorNaam" placeholder="VoorNaam">

                <input type="text" name="AchterNaam" id="AchterNaam" placeholder="AchterNaam">

                <input type="text" name="Tussenvoegsels" id="Tussenvoegsels" placeholder="Tussenvoegsels">

                <input type="text" name="email" id="email" placeholder="e-mail">

                <input type="text" name="Wachtwoord" placeholder="Wachtwoord">

                <input type="text" name="HerhaalWachtwoord" placeholder="Herhaal Wachtwoord">

                <select name="Positie" id="Positie">
                    <option value="medewerker">medewerker</option>
                    <option value="vrijwilliger">Vrijwilliger</option>
                    <option value="directie">directie</option>
                </select>
                <button type="submit" id="updategbrBtn" name="update_gebruiker" style="display: none;">Bijwerken</button>
                <button type="submit" id="addgbrBtn" name="submit">Registreer</button>
            </form>
        </section>
        <h1>Gebruiker Overzicht</h1>
<table id="leveranciersTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>voornaam</th>
            <th>Tussenvoegsels</th>
            <th>Achternaam</th>
            <th>Email</th>
            <th>Postitie</th>
            <th>Bewerken</th>
        </tr>
    </thead>
    <tbody>

<?php


// Controleer of er een zoekterm is opgegeven in de URL en ontsmet deze voor veiligheid
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Basis SQL-query om alle gebruikers op te halen
$query = "SELECT * FROM gebruikers";

// Als er een zoekterm is opgegeven, voeg dan een WHERE-clausule toe aan de query
if (!empty($search)) {
    $query .= " WHERE voornaam LIKE '%$search%'";
}
// Voer de query uit en sla het resultaat op in $result
$result = $conn->query($query);

// Controleer of er rijen zijn opgehaald
if ($result->num_rows > 0) {
    // Loop door elke rij in het resultaat    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        // Toon de gegevens van de gebruiker in tabelcellen        
        echo "<td>" . htmlspecialchars($row['voornaam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tussenvoegsels']) . "</td>";
        echo "<td>" . htmlspecialchars($row['achternaam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['positie']) . "</td>";
        echo "<td><a href='?edit=" . htmlspecialchars($row['gebruikerid']) . "'>Bewerken</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Geen gegevens gevonden</td></tr>";
}
?>
    </tbody>
</table>

<?php

// Controleer of er een 'edit' parameter is opgegeven in de URL
if (isset($_GET['edit'])) {

    // Haal het gebruikerid op uit de URL en ontsmet deze voor veiligheid
    $gebruikerid = $conn->real_escape_string($_GET['edit']);

    // Bereid de SQL-query voor om de gegevens van de geselecteerde gebruiker op te halen
    $edit_query = "SELECT * FROM gebruikers WHERE gebruikerid = '$gebruikerid'";
    $edit_result = $conn->query($edit_query);

    // Controleer of er een resultaat is gevonden
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();
?>
    <script>
        // Vul de formuliervelden met de gegevens van de geselecteerde gebruiker
        document.getElementById("gebruikerid").value = "<?php echo htmlspecialchars($edit_row['gebruikerid']); ?>";
        document.getElementById("VoorNaam").value = "<?php echo htmlspecialchars($edit_row['voornaam']); ?>";
        document.getElementById("AchterNaam").value = "<?php echo htmlspecialchars($edit_row['achternaam']); ?>";
        document.getElementById("Tussenvoegsels").value = "<?php echo htmlspecialchars($edit_row['tussenvoegsels']); ?>";
        document.getElementById("email").value = "<?php echo htmlspecialchars($edit_row['email']); ?>";
        document.getElementById("Positie").value = "<?php echo htmlspecialchars($edit_row['positie']); ?>";

        // Verberg de 'Toevoegen' knop en toon de 'Bijwerken' knop
        document.getElementById("addgbrBtn").style.display = "none";
        document.getElementById("updategbrBtn").style.display = "inline";
    </script>
<?php
    }
}
// Sluit de databaseverbinding
$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialiseer de DataTable
        $('#leveranciersTable').DataTable();
    });
</script>

    </main>
</body>
</html>
