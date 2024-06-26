<?php include("header.php") ?>
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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examenvoedselbank";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_leverancier'])) {
        $bedrijfnaam = $conn->real_escape_string($_POST['bedrijfnaam']);
        $adress = $conn->real_escape_string($_POST['adress']);
        $bedrijfemail = $conn->real_escape_string($_POST['bedrijfemail']);
        $telefoonnummer = $conn->real_escape_string($_POST['telefoonnummer']);
        $contactpersoon = $conn->real_escape_string($_POST['contactpersoon']);
        $leveringdatum = $conn->real_escape_string($_POST['leveringdatum']);
        $volgendelevering = $conn->real_escape_string($_POST['volgendelevering']);
        $insert_query = "INSERT INTO leveranciers (bedrijfnaam, adress, bedrijfemail, telefoonnummer, contactpersoon, leveringdatum, volgendelevering) VALUES ('$bedrijfnaam', '$adress', '$bedrijfemail', '$telefoonnummer', '$contactpersoon', '$leveringdatum', '$volgendelevering')";
        
        if ($conn->query($insert_query) === TRUE) {
            $_SESSION['success_message'] = "De leverancier is succesvol toegevoegd.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Fout bij het toevoegen van de leverancier: " . $conn->error;
        }
    }

    if (isset($_POST['update_leverancier'])) {
        $leverancierid = $conn->real_escape_string($_POST['leverancierid']);
        $bedrijfnaam = $conn->real_escape_string($_POST['bedrijfnaam']);
        $adress = $conn->real_escape_string($_POST['adress']);
        $bedrijfemail = $conn->real_escape_string($_POST['bedrijfemail']);
        $telefoonnummer = $conn->real_escape_string($_POST['telefoonnummer']);
        $contactpersoon = $conn->real_escape_string($_POST['contactpersoon']);
        $leveringdatum = $conn->real_escape_string($_POST['leveringdatum']);
        $volgendelevering = $conn->real_escape_string($_POST['volgendelevering']);
        $update_query = "UPDATE leveranciers SET bedrijfnaam='$bedrijfnaam', adress='$adress', bedrijfemail='$bedrijfemail', telefoonnummer='$telefoonnummer', contactpersoon='$contactpersoon', leveringdatum='$leveringdatum', volgendelevering='$volgendelevering' WHERE leverancierid='$leverancierid'";
    
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

$query = "SELECT * FROM leveranciers";
if (!empty($search)) {
    $query .= " WHERE bedrijfnaam LIKE '%$search%'";
}

$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['bedrijfnaam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['adress']) . "</td>";
        echo "<td>" . htmlspecialchars($row['bedrijfemail']) . "</td>";
        echo "<td>" . htmlspecialchars($row['telefoonnummer']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contactpersoon']) . "</td>";
        echo "<td>" . htmlspecialchars($row['leveringdatum']) . "</td>";
        echo "<td>" . htmlspecialchars($row['volgendelevering']) . "</td>";
        echo "<td><a href='?edit=" . htmlspecialchars($row['leverancierid']) . "'>Bewerken</a></td>";
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
    $leverancierid = $conn->real_escape_string($_GET['edit']);
    $edit_query = "SELECT * FROM leveranciers WHERE leverancierid = '$leverancierid'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();
?>
    <script>
        document.getElementById("leverancierid").value = "<?php echo htmlspecialchars($edit_row['leverancierid']); ?>";
        document.getElementById("bedrijfnaam").value = "<?php echo htmlspecialchars($edit_row['bedrijfnaam']); ?>";
        document.getElementById("adress").value = "<?php echo htmlspecialchars($edit_row['adress']); ?>";
        document.getElementById("bedrijfemail").value = "<?php echo htmlspecialchars($edit_row['bedrijfemail']); ?>";
        document.getElementById("telefoonnummer").value = "<?php echo htmlspecialchars($edit_row['telefoonnummer']); ?>";
        document.getElementById("contactpersoon").value = "<?php echo htmlspecialchars($edit_row['contactpersoon']); ?>";
        document.getElementById("leveringdatum").value = "<?php echo htmlspecialchars($edit_row['leveringdatum']); ?>";
        document.getElementById("volgendelevering").value = "<?php echo htmlspecialchars($edit_row['volgendelevering']); ?>";

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
        $('#leveranciersTable').DataTable();

        $('#updateLeverancierBtn').hide();

        <?php if (isset($_GET['edit'])): ?>
        $('#addLeverancierBtn').hide();
        $('#updateLeverancierBtn').show();
        <?php endif; ?>
    });
</script>

</body>
</html>


