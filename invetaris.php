<?php include("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/invetaris.css">
    <title>invetaris</title>
</head>
<body class="background">

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examenvoedselbank";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_product'])) {
    $product = $conn->real_escape_string($_POST['product']);
    $aantal = $conn->real_escape_string($_POST['aantal']);
    $producttype = $conn->real_escape_string($_POST['producttype']);
    $allergieën = $conn->real_escape_string(implode(', ', $_POST['allergieën']));
    $locatie = $conn->real_escape_string($_POST['locatie']);
    $houdsbaarheidsdatum = $conn->real_escape_string($_POST['houdsbaarheidsdatum']);
    $Leveringdatum = $conn->real_escape_string($_POST['leveringsdatum']);
    $streepjescode = $conn->real_escape_string($_POST['streepjescode']);

    $insert_query = "INSERT INTO invetaris (product, aantal, producttype, allergieën, locatie, houdsbaarheidsdatum,leveringsdatum,streepjescode) VALUES ('$product', '$aantal', '$producttype', '$allergieën', '$locatie', '$houdsbaarheidsdatum','$Leveringdatum','$streepjescode')";

    if ($conn->query($insert_query) === TRUE) {
        $_SESSION['message'] = "Product succesvol toegevoegd!";
    } else {
        $_SESSION['error'] = "Fout bij het toevoegen van het product: " . $conn->error;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['update_product'])) {
    $product = $conn->real_escape_string($_POST['product']);
    $aantal = $conn->real_escape_string($_POST['aantal']);
    $producttype = $conn->real_escape_string($_POST['producttype']);
    $allergieën = $conn->real_escape_string(implode(', ', $_POST['allergieën']));
    $locatie = $conn->real_escape_string($_POST['locatie']);
    $houdsbaarheidsdatum = $conn->real_escape_string($_POST['houdsbaarheidsdatum']);
    $Leveringdatum = $conn->real_escape_string($_POST['leveringsdatum']);
    $streepjescode = $conn->real_escape_string($_POST['streepjescode']);
    $productid = $conn->real_escape_string($_POST['productid']);
    $update_query = "UPDATE invetaris SET product='$product', aantal='$aantal', producttype='$producttype', allergieën='$allergieën', locatie='$locatie',leveringsdatum='$Leveringdatum',houdsbaarheidsdatum='$houdsbaarheidsdatum', streepjescode='$streepjescode' WHERE productid='$productid'";
    

    if ($conn->query($update_query) === TRUE) {
        $_SESSION['message'] = "Product succesvol bijgewerkt!";
    } else {
        $_SESSION['error'] = "Fout bij het bijwerken van het product: " . $conn->error;
    }
    echo "<scrypt> window.location='invetaris.php'</script>";
    exit;
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM invetaris";
if (!empty($search)) {
    $query .= " WHERE streepjescode LIKE '%$search%'";
}
$query .= " GROUP BY invetaris.product, invetaris.aantal, invetaris.producttype, invetaris.locatie, invetaris.streepjescode, invetaris.houdsbaarheidsdatum, invetaris.leveringsdatum";

$result = $conn->query($query);
?>

<h2>Nieuw Product Toevoegen</h2>
<form method="POST" action="">
<input type="hidden" id="productid" name="productid" value="<?php echo isset($edit_row['productid']) ? htmlspecialchars($edit_row['productid']) : ''; ?>">
    <input type="text" id="product" name="product" placeholder="Product" required>
    <input type="number" id="aantal" name="aantal" placeholder="Aantal" required>
    <select id="producttype" name="producttype" required>
        <option value="">Kies een producttype</option>
        <option value="groenten">Groenten</option>
        <option value="vleeswaren">Vleeswaren</option>
        <option value="fruit">Fruit</option>
        <option value="vis">Vis</option>
        <option value="pasta">Pasta</option>
        <option value="zuivel">Zuivel</option>
        <option value="aardappelen">Aardappelen</option>
        <option value="kaas">Kaas</option>
        <option value="plantaardig en eiren">Plantaardig en eiren</option>
        <option value="bakkerij en banket">Bakkerij en banket</option>
        <option value="frisdrank">frisdrank</option>
        <option value="sappen">Sappen</option>
        <option value="koffie en thee">Koffie en thee</option>
        <option value="pasta">Pasta</option>
        <option value="rijst en wereldkeuken">Rijst en wereldkeuken</option>
        <option value="soepen">Soepen</option>
        <option value="sauzen">Sauzen</option>
        <option value="kruiden en olie">Kruiden en olie</option>
        <option value="snoep">Snoep</option>
        <option value="koek">Koek</option>
        <option value="chips en chocolade">Chips en chocolade</option>
        <option value="baby">Baby</option>
        <option value="verzorging en hygiene">Verzorging en hygiene</option>
        <option value="overig">Overig</option>
    </select>
    <input type="text" id="locatie" name="locatie" placeholder="Locatie" required>
    <input type="date" id="houdsbaarheidsdatum" name="houdsbaarheidsdatum" placeholder="Houdsbaarheidsdatum" required>
    <input type="text" id="streepjescode" name="streepjescode" placeholder="Streepjescode" required>
    <div>
        <label><input type="checkbox" name="allergieën[]" value="gluten"> Gluten</label>
        <label><input type="checkbox" name="allergieën[]" value="pindas"> Pinda's</label>
        <label><input type="checkbox" name="allergieën[]" value="schaaldieren"> Schaaldieren</label>
        <label><input type="checkbox" name="allergieën[]" value="hazelnoten"> Hazelnoten</label>
        <label><input type="checkbox" name="allergieën[]" value="lactose"> Lactose</label>
        <label><input type="text" name="allergieën[]" value="" placeholder="overig"></label>
    </div>
    <p>leveringsdatum:</p><input type="date" name="leveringsdatum" placeholder="Leveringsdatum" required>
    <button type="submit" id="addproduct" name="add_product">Toevoegen</button>
    <button type="submit" id="updateproduct" name="update_product" style="display: none;">Bijwerken</button>
</form>

<h1>Product Aantal Overzicht</h1>
<table id="productTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>Product</th>
            <th>Aantal</th>
            <th>ProductType</th>
            <th>Allergieën</th>
            <th>Locatie</th>
            <th>Houdsbaarheidsdatum</th>
            <th>leveringsdatum</th>
            <th>Streepjescode</th>
            <th>Bewerken</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['product']) . "</td>";
                echo "<td>" . htmlspecialchars($row['aantal']) . "</td>";
                echo "<td>" . htmlspecialchars($row['producttype']) . "</td>";
                echo "<td>" . htmlspecialchars($row['allergieën']) . "</td>";
                echo "<td>" . htmlspecialchars($row['locatie']) . "</td>";
                echo "<td>" . htmlspecialchars($row['houdsbaarheidsdatum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['leveringsdatum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['streepjescode']) . "</td>";
                echo "<td><a href='?edit=" . htmlspecialchars($row['productid']) . "'>Bewerken</a></td>";
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
    $productid = $conn->real_escape_string($_GET['edit']);
    $edit_query = "SELECT * FROM invetaris WHERE productid = '$productid'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();
?>
    <script>
        document.getElementById("productid").value = "<?php echo isset($edit_row['productid']) ? htmlspecialchars($edit_row['productid']) : ''; ?>";
        document.getElementById("product").value = "<?php echo htmlspecialchars($edit_row['product']); ?>";
        document.getElementById("aantal").value = "<?php echo htmlspecialchars($edit_row['aantal']); ?>";
        document.getElementById("producttype").value = "<?php echo htmlspecialchars($edit_row['producttype']); ?>";
       
        var allergieën = "<?php echo htmlspecialchars($edit_row['allergieën']); ?>".split(', ');
        document.querySelectorAll("input[name='allergieën[]']").forEach(function(checkbox) {
            if (allergieën.includes(checkbox.value)) {
                checkbox.checked = true;
            }
        });
        document.getElementById("locatie").value = "<?php echo htmlspecialchars($edit_row['locatie']); ?>";
        document.getElementById("houdsbaarheidsdatum").value = "<?php echo htmlspecialchars($edit_row['houdsbaarheidsdatum']); ?>";
        document.getElementById("streepjescode").value = "<?php echo htmlspecialchars($edit_row['streepjescode']); ?>";

        document.getElementById("addproduct").style.display = "none";
        document.getElementById("updateproduct").style.display = "inline";
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
        $('#productTable').DataTable();

        $('#updateproduct').hide();

        <?php if (isset($_GET['edit'])): ?>
        $('#addproduct').hide();
        $('#updateproduct').show();
        <?php endif; ?>
    });
</script>

</body>
</html>
