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
    <input type="number" name="gezingroote" placeholder="Gezinsgrootte" required>
    <input type="text" name="leeftijd" placeholder="Leeftijd" required>
    <input type="text" name="allergieën" placeholder="Allergieën" required>

    <div class="dropdown" id="voorkeurenDropdown">
        
        <div class="dropdown-content" id="voorkeurenContent">
            <label><input type="checkbox" name="voorkeuren[]" value="halal"> Halal</label>
            <label><input type="checkbox" name="voorkeuren[]" value="vegetarisch"> Vegetarisch</label>
            <label><input type="checkbox" name="voorkeuren[]" value="veganistisch"> Veganistisch</label>
        </div>
    </div>

    <input type="date" name="uitgiftedatum" placeholder="Uitgiftedatum" required>
    <button type="submit" name="add_klant">Toevoegen</button>
</form>

<h1>Klanten Overzicht</h1>
<table id="klantenTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>Gezin ID</th>
            <th>Naam</th>
            <th>Gezinsgrootte</th>
            <th>Leeftijd</th>
            <th>Allergieën</th>
            <th>Voorkeuren</th>
            <th>Uitgiftedatum</th>
            <th>Bewerken</th>
        </tr>
    </thead>
    <tbody>
    <?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examenvoedselbank";
$conn = new mysqli($servername, $username, $password, $dbname, 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_klant'])) {
        $naam = $conn->real_escape_string($_POST['naam']);
        $gezingroote = $conn->real_escape_string($_POST['gezingroote']);
        $leeftijden = $conn->real_escape_string($_POST['leeftijd']);
        $allergieën = $conn->real_escape_string($_POST['allergieën']);
        $voorkeuren = implode(', ', $_POST['voorkeuren']);
        $uitgiftedatum = $conn->real_escape_string($_POST['uitgiftedatum']);
        
        $insert_query = "INSERT INTO klanten (naam, gezingroote, leeftijd, allergieën, voorkeuren, uitgiftedatum) VALUES ('$naam', '$gezingroote', '$leeftijden', '$allergieën', '$voorkeuren', '$uitgiftedatum')";
        
        if ($conn->query($insert_query) === TRUE) {
            $_SESSION['success_message'] = "De klant is succesvol toegevoegd.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Fout bij het toevoegen van de klant: " . $conn->error;
        }
    }
}
?>

        <?php
        
        if (isset($_POST['update_klant'])) {
            $gezinid = $conn->real_escape_string($_POST['gezinid']);
            $naam = $conn->real_escape_string($_POST['naam']);
            $gezingroote = $conn->real_escape_string($_POST['gezingroote']);
            $leeftijd = $conn->real_escape_string($_POST['leeftijd']);
            $allergieën = $conn->real_escape_string($_POST['allergieën']);
            $voorkeuren = implode(', ', $_POST['voorkeuren']);
            $uitgiftedatum = $conn->real_escape_string($_POST['uitgiftedatum']);
        
            $update_query = "UPDATE klanten SET naam='$naam', gezingroote='$gezingroote', leeftijd='$leeftijd', allergieën='$allergieën', voorkeuren='$voorkeuren', uitgiftedatum='$uitgiftedatum' WHERE gezinid='$gezinid'";
        
            if ($conn->query($update_query) === TRUE) {
                echo "";
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
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['gezinid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['naam']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gezingroote']) . "</td>";
                echo "<td>" . htmlspecialchars($row['leeftijd']) . "</td>";
                echo "<td>" . htmlspecialchars($row['allergieën']) . "</td>";
                echo "<td>" . htmlspecialchars($row['voorkeuren']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uitgiftedatum']) . "</td>";
                echo "<td><a href='?edit=" . htmlspecialchars($row['gezinid']) . "'>Bewerken</a></td>";
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
        <input type="number" name="gezingroote" placeholder="Gezinsgrootte" value="<?php echo htmlspecialchars($edit_row['gezingroote']); ?>" required>
        <input type="number" name="leeftijd" placeholder="Leeftijd" value="<?php echo htmlspecialchars($edit_row['leeftijd']); ?>" required>
        <input type="text" name="allergieën" placeholder="Allergieën" value="<?php echo htmlspecialchars($edit_row['allergieën']); ?>" required>
        
        <div class="dropdown">
            <input type="text" name="voorkeuren_display" id="voorkeurenInputEdit" placeholder="Voorkeuren" readonly value="<?php echo htmlspecialchars($edit_row['voorkeuren']); ?>">
            <div class="dropdown-content">
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
