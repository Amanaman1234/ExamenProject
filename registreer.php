<?php include("header.php") ?>
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

                <select  name="Positie" id="Positie" placeholder="positie">
                    <option value="medewerker">medewerker</option>
                    <option value="vrijwilliger">Vrijwilliger</option>
                    <option value="directie">directie</option>
                             </select>
                <button type="submit" id="updategbrBtn" name="update_gebruiker" style="display: none;">Bijwerken</button>
                <button type="submit" id="addgbrBtn" name="submit">Registreer</button>
            </form>
        </section>
        <h1>Leveranciers Overzicht</h1>
<table id="leveranciersTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>voornaam</th>
            <th>Achternaam</th>
            <th>Tussenvoegsels</th>
            <th>Email</th>
            <th>Postitie</th>
            <th>Bewerken</th>
        </tr>
    </thead>
    <tbody>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['update_gebruiker'])) {
        $gebruikerid  = $conn->real_escape_string($_POST['gebruikerid']);
        $voornaam = $conn->real_escape_string($_POST['VoorNaam']);
        $AchterNaam = $conn->real_escape_string($_POST['AchterNaam']);
        $Tussenvoegsels = $conn->real_escape_string($_POST['Tussenvoegsels']);
        $email = $conn->real_escape_string($_POST['email']);
        $Positie = $conn->real_escape_string($_POST['Positie']);

        $update_query = "UPDATE gebruikers SET voornaam='$voornaam', AchterNaam='$AchterNaam', Tussenvoegsels='$Tussenvoegsels', email='$email', Positie='$Positie'  WHERE gebruikerid ='$gebruikerid '";
    
        if ($conn->query($update_query) === TRUE) {
            $_SESSION['success_message'] = "De leverancier is succesvol bijgewerkt.";
            echo '<script>
                    alert("De leverancier is succesvol bijgewerkt.");
                    window.location.href = window.location.href.split("?")[0];
                  </script>';
            exit();
        } else {
            echo "Fout bij het bijwerken van de leverancier: " . $conn->error;
        }
    }
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM gebruikers";
if (!empty($search)) {
    $query .= " WHERE voornaam LIKE '%$search%'";
}

$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['voornaam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['achternaam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tussenvoegsels']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['positie']) . "</td>";
        echo "<td><a href='?edit=" . htmlspecialchars($row['gebruikerid']) . "'>Bewerken</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Geen gegevens gevonden</td></tr>";
}
?>
    </tbody>
</table>

<?php
if (isset($_GET['edit'])) {
    $gebruikerid  = $conn->real_escape_string($_GET['edit']);
    $edit_query = "SELECT * FROM gebruikers WHERE gebruikerid  = '$gebruikerid'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();
?>
    <script>
        document.getElementById("gebruikerid ").value = "<?php echo htmlspecialchars($edit_row['gebruikerid']); ?>";
        document.getElementById("VoorNaam").value = "<?php echo htmlspecialchars($edit_row['voornaam']); ?>";
        document.getElementById("AchterNaam").value = "<?php echo htmlspecialchars($edit_row['achternaam']); ?>";
        document.getElementById("Tussenvoegsels").value = "<?php echo htmlspecialchars($edit_row['tussenvoegsels']); ?>";
        document.getElementById("email").value = "<?php echo htmlspecialchars($edit_row['email']); ?>";
        document.getElementById("Positie").value = "<?php echo htmlspecialchars($edit_row['positie']); ?>";


        document.getElementById("addgbrBtn").style.display = "none";
        document.getElementById("updategbrBtn").style.display = "inline";
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
        $('#leveranciersTable').DataTable();

        $('#updategbrBtn').hide();

        <?php if (isset($_GET['edit'])): ?>
        $('#addgbrBtn').hide();
        $('#updategbrBtn').show();
        <?php endif; ?>
    });
</script>

    </main>
</body>
</html>

