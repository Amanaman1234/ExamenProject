<?php
include("header.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examenvoedselbank";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_klant'])) {
        $naam = $conn->real_escape_string($_POST['naam']);
        $adres = $conn->real_escape_string($_POST['adres']);
        $postcode = $conn->real_escape_string($_POST['postcode']);
        $telefoonnummer = $conn->real_escape_string($_POST['telefoonnummer']);
        $emailadres = $conn->real_escape_string($_POST['emailadres']);
        $leeftijd_onder_2 = $conn->real_escape_string($_POST['leeftijd_onder_2']);
        $leeftijd_2_tot_18 = $conn->real_escape_string($_POST['leeftijd_2_tot_18']);
        $leeftijd_boven_18 = $conn->real_escape_string($_POST['leeftijd_boven_18']);
        $allergieën = $conn->real_escape_string(implode(', ', $_POST['allergieën']));
        $voorkeuren = $conn->real_escape_string(implode(', ', $_POST['voorkeuren']));
        $uitgiftedatum = $conn->real_escape_string($_POST['uitgiftedatum']);

        $insert_query = "INSERT INTO klanten (naam, adres, postcode, telefoonnummer, emailadres, leeftijd_onder_2, leeftijd_2_tot_18, leeftijd_boven_18, allergieën, voorkeuren, uitgiftedatum) VALUES ('$naam', '$adres', '$postcode','$telefoonnummer', '$emailadres', '$leeftijd_onder_2', '$leeftijd_2_tot_18', '$leeftijd_boven_18', '$allergieën', '$voorkeuren', '$uitgiftedatum')";

        if ($conn->query($insert_query) === TRUE) {
            $_SESSION['success_message'] = "De klant is succesvol toegevoegd.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Fout bij het toevoegen van de klant: " . $conn->error;
        }
    }
}

if (isset($_POST['update_klant'])) {
    $naam = $conn->real_escape_string($_POST['naam']);
    $adres = $conn->real_escape_string($_POST['adres']);
    $postcode = $conn->real_escape_string($_POST['postcode']);
    $telefoonnummer = $conn->real_escape_string($_POST['telefoonnummer']);
    $emailadres = $conn->real_escape_string($_POST['emailadres']);
    $leeftijd_onder_2 = $conn->real_escape_string($_POST['leeftijd_onder_2']);
    $leeftijd_2_tot_18 = $conn->real_escape_string($_POST['leeftijd_2_tot_18']);
    $leeftijd_boven_18 = $conn->real_escape_string($_POST['leeftijd_boven_18']);
    $allergieën = $conn->real_escape_string(implode(', ', $_POST['allergieën']));
    $voorkeuren = $conn->real_escape_string(implode(', ', $_POST['voorkeuren']));
    $uitgiftedatum = $conn->real_escape_string($_POST['uitgiftedatum']);

    $update_query = "UPDATE klanten SET naam='$naam', adres='$adres', postcode='$postcode', telefoonnummer='$telefoonnummer', emailadres='$emailadres', gezingroote='$gezingroote', leeftijd_onder_2='$leeftijd_onder_2', leeftijd_2_tot_18='$leeftijd_2_tot_18', leeftijd_boven_18='$leeftijd_boven_18', allergieën='$allergieën', voorkeuren='$voorkeuren', uitgiftedatum='$uitgiftedatum' WHERE gezinid='$gezinid'";

    if ($conn->query($update_query) === TRUE) {
        $_SESSION['success_message'] = "De klant is succesvol bijgewerkt.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "Fout bij het bijwerken van de klant: " . $conn->error;
    }
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM klanten";
if (!empty($search)) {
    $query .= " WHERE naam LIKE '%$search%'";
}

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/klanten.css">
    <title>Klanten</title>
</head>
<body class="background">

<h2>Nieuwe Klant Toevoegen</h2>
<form method="POST" action="">
    <input type="text" name="naam" placeholder="Naam" required>
    <input type="text" name="adres" placeholder="adres" required>
    <input type="text" name="postcode" placeholder="postcode" required>
    <input type="tel" name="telefoonnummer" placeholder="telefoonnummer" required>
    <input type="text" name="emailadres" placeholder="emailadres" required>
    <div>
        <label>Onder 2 jaar: <input type="number" name="leeftijd_onder_2" min="0" value="0" required></label>
        <label>2 tot 18 jaar: <input type="number" name="leeftijd_2_tot_18" min="0" value="0" required></label>
        <label>Boven 18 jaar: <input type="number" name="leeftijd_boven_18" min="0" value="0" required></label>
    </div>
    <div>
        <label><input type="checkbox" name="allergieën[]" value="gluten"> Gluten</label>
        <label><input type="checkbox" name="allergieën[]" value="pindas"> Pinda's</label>
        <label><input type="checkbox" name="allergieën[]" value="schaaldieren"> Schaaldieren</label>
        <label><input type="checkbox" name="allergieën[]" value="hazelnoten"> Hazelnoten</label>
        <label><input type="checkbox" name="allergieën[]" value="lactose"> Lactose</label>
        <label><input type="text" name="allergieën[]" value="" placeholder="overig"></label>
    </div>
    <div>
        <label><input type="checkbox" name="voorkeuren[]" value="halal"> Halal</label>
        <label><input type="checkbox" name="voorkeuren[]" value="vegetarisch"> Vegetarisch</label>
        <label><input type="checkbox" name="voorkeuren[]" value="veganistisch"> Veganistisch</label>
    </div>
    <input type="date" name="uitgiftedatum" placeholder="Uitgiftedatum" required>
    <button type="submit" name="add_klant">Toevoegen</button>
</form>

<h1>Klanten Overzicht</h1>
<table id="klantenTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>Naam</th>
            <th>adres</th>
            <th>postcode</th>
            <th>telefoonnummer</th>
            <th>emailadres</th>
            <th>Onder 2 jaar</th>
            <th>2 tot 18 jaar</th>
            <th>Boven 18 jaar</th>
            <th>Allergieën</th>
            <th>Voorkeuren</th>
            <th>Uitgiftedatum</th>
            <th>Bewerken</th>
        </tr>
    </thead>
    <tbody>

<?php

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['naam']) . "</td>";
        echo "<td>" . htmlspecialchars($row['adres']) . "</td>";
        echo "<td>" . htmlspecialchars($row['postcode']) . "</td>";
        echo "<td>" . htmlspecialchars($row['telefoonnummer']) . "</td>";
        echo "<td>" . htmlspecialchars($row['emailadres']) . "</td>";
        echo "<td>" . htmlspecialchars($row['leeftijd_onder_2']) . "</td>";
        echo "<td>" . htmlspecialchars($row['leeftijd_2_tot_18']) . "</td>";
        echo "<td>" . htmlspecialchars($row['leeftijd_boven_18']) . "</td>";
        echo "<td>" . htmlspecialchars($row['allergieën']) . "</td>";
        echo "<td>" . htmlspecialchars($row['voorkeuren']) . "</td>";
        echo "<td>" . htmlspecialchars($row['uitgiftedatum']) . "</td>";
        echo "<td><a href='?edit=" . htmlspecialchars($row['gezinid']) . "'>Bewerken</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='13'>Geen gegevens gevonden</td></tr>";
}
?>
    </tbody>
</table>

<?php
if (isset($_GET['edit'])) {
    $gezinid = $conn->real_escape_string($_GET['edit']);
    $edit_query = "SELECT * FROM klanten WHERE gezinid = '$gezinid'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();
        $selected_voorkeuren = explode(", ", $edit_row['voorkeuren']);
?>
    <h2>Klant Bewerken</h2>
    <form method="POST" action="">
        <input type="hidden" name="gezinid" value="<?php echo htmlspecialchars($edit_row['gezinid']); ?>">
        <input type="text" name="naam" placeholder="Naam" value="<?php echo htmlspecialchars($edit_row['naam']); ?>" required>
        <input type="text" name="postcode" placeholder="postcode" value="<?php echo htmlspecialchars($edit_row['postcode']); ?>" required>
        <input type="text" name="adres" placeholder="adres" value="<?php echo htmlspecialchars($edit_row['adres']); ?>" required>
        <input type="tel" name="telefoonnummer" placeholder="telefoonnummer" value="<?php echo htmlspecialchars($edit_row['telefoonnummer']); ?>" required>
        <input type="text" name="emailadres" placeholder="emailadres" value="<?php echo htmlspecialchars($edit_row['emailadres']); ?>" required>
        <div>
            <label>Onder 2 jaar: <input type="number" name="leeftijd_onder_2" min="0" value="<?php echo htmlspecialchars($edit_row['leeftijd_onder_2']); ?>" required></label>
            <label>2 tot 18 jaar: <input type="number" name="leeftijd_2_tot_18" min="0" value="<?php echo htmlspecialchars($edit_row['leeftijd_2_tot_18']); ?>" required></label>
            <label>Boven 18 jaar: <input type="number" name="leeftijd_boven_18" min="0" value="<?php echo htmlspecialchars($edit_row['leeftijd_boven_18']); ?>" required></label>
        </div>
        <input type="text" name="allergieën" placeholder="Allergieën" value="<?php echo htmlspecialchars($edit_row['allergieën']); ?>" required>

        <div class="dropdown">
            <input type="text" name="voorkeuren_display" placeholder="Voorkeuren" readonly value="<?php echo htmlspecialchars($edit_row['voorkeuren']); ?>">
            <div>
                <label><input type="checkbox" name="voorkeuren[]" value="halal" <?php echo in_array("halal", $selected_voorkeuren) ? "checked" : ""; ?>> Halal</label>
                <label><input type="checkbox" name="voorkeuren[]" value="vegetarisch" <?php echo in_array("vegetarisch", $selected_voorkeuren) ? "checked" : ""; ?>> Vegetarisch</label>
                <label><input type="checkbox" name="voorkeuren[]" value="veganistisch" <?php echo in_array("veganistisch", $selected_voorkeuren) ? "checked" : ""; ?>> Veganistisch</label>
            </div>
        </div>

        <input type="date" name="uitgiftedatum" placeholder="Uitgiftedatum" value="<?php echo htmlspecialchars($edit_row['uitgiftedatum']); ?>" required>
        <button type="submit" name="update_klant">Bijwerken</button>
    </form>
<?php
    }
}
$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#klantenTable').DataTable();
    });
</script>

</body>
</html>


