<?php include("header.php") ?>
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


<h2>Nieuw Product Toevoegen</h2>
<form method="POST" action="">
    <input type="text" name="product" placeholder="Product" required>
    <input type="number" name="aantal" placeholder="Aantal" required>
    <select name="producttype" required>
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
    <input type="text" name="locatie" placeholder="Locatie" required>
    <input type="date" name="houdsbaarheidsdatum" placeholder="Houdsbaarheidsdatum" required>
    <input type="text" name="streepjescode" placeholder="Streepjescode" required>
    <div>
        <label><input type="checkbox" name="allergieën[]" value="gluten"> Gluten</label>
        <label><input type="checkbox" name="allergieën[]" value="pindas"> Pinda's</label>
        <label><input type="checkbox" name="allergieën[]" value="schaaldieren"> Schaaldieren</label>
        <label><input type="checkbox" name="allergieën[]" value="hazelnoten"> Hazelnoten</label>
        <label><input type="checkbox" name="allergieën[]" value="lactose"> Lactose</label>
        <label><input type="text" name="allergieën[]" value="" placeholder="overig"></label>
    </div>
    <button type="submit" name="add_product">Toevoegen</button>
    
</form>

<h1>Product Aantal Overzicht</h1>
<table id="productTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>Product</th>
            <th>Aantal</th>
            <th>ProductType</th>
            <th>allergieën</th>
            <th>locatie</th>
            <th>houdsbaarheidsdatum</th>
            <th>streepjescode</th>
            <th>bewerken</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "examenvoedselbank";
        $conn = new mysqli($servername, $username, $password, $dbname,3307 );
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
            $streepjescode = $conn->real_escape_string($_POST['streepjescode']);

            $insert_query = "INSERT INTO invetaris (product, aantal, producttype, allergieën, locatie, houdsbaarheidsdatum, streepjescode) VALUES ('$product', '$aantal', '$producttype', '$allergieën', '$locatie', '$houdsbaarheidsdatum', '$streepjescode')";

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
            $allergieën = $conn->real_escape_string($_POST['allergieën']);
            $locatie = $conn->real_escape_string($_POST['locatie']);
            $houdsbaarheidsdatum = $conn->real_escape_string($_POST['houdsbaarheidsdatum']);
            $streepjescode = $conn->real_escape_string($_POST['streepjescode']);

            $update_query = "INSERT INTO invetaris (product, aantal, producttype, allergieën, locatie, houdsbaarheidsdatum, streepjescode) VALUES ('$product', '$aantal', '$producttype', '$allergieën', '$locatie', '$houdsbaarheidsdatum', '$streepjescode')";
            
                if ($conn->query($update_query) === TRUE) {
                $_SESSION['message'] = "Product succesvol bijgewerkt!";
            } else {
                $_SESSION['error'] = "Fout bij het bijwerken van het product: " . $conn->error;
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

        $query = "SELECT * FROM invetaris";
        if (!empty($search)) {
            $query .= " WHERE streepjescode LIKE '%$search%'";
        }
        $query .= " GROUP BY invetaris.product, invetaris.aantal, invetaris.producttype, invetaris.locatie, invetaris.streepjescode, invetaris.houdsbaarheidsdatum";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['product']) . "</td>";
                echo "<td>" . htmlspecialchars($row['aantal']) . "</td>";
                echo "<td>" . htmlspecialchars($row['producttype']) . "</td>";
                echo "<td>" . htmlspecialchars($row['allergieën']) . "</td>";
                echo "<td>" . htmlspecialchars($row['locatie']) . "</td>";
                echo "<td>" . htmlspecialchars($row['houdsbaarheidsdatum']) . "</td>";
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
    $edit_row = $edit_result->fetch_assoc();
?>
    <h2>Product Bewerken</h2>
    <form method="POST" action="">
        <input type="text" name="product" placeholder="Product" value="<?php echo htmlspecialchars($edit_row['product']); ?>" required>
        <input type="number" name="aantal" placeholder="Aantal" value="<?php echo htmlspecialchars($edit_row['aantal']); ?>" required>
        <select name="producttype" required>
        
            <option value="">Kies een producttype</option>
            <option value="groenten" <?php echo ($edit_row['producttype'] == 'groenten') ? 'selected' : ''; ?>>Groenten</option>
            <option value="vleeswaren" <?php echo ($edit_row['producttype'] == 'vleeswaren') ? 'selected' : ''; ?>>Vleeswaren</option>
            <option value="fruit" <?php echo ($edit_row['producttype'] == 'fruit') ? 'selected' : ''; ?>>Fruit</option>
            <option value="vis" <?php echo ($edit_row['producttype'] == 'vis') ? 'selected' : ''; ?>>Vis</option>
            <option value="pasta" <?php echo ($edit_row['producttype'] == 'pasta') ? 'selected' : ''; ?>>Pasta</option>
            <option value="zuivel" <?php echo ($edit_row['producttype'] == 'zuivel') ? 'selected' : ''; ?>>Zuivel</option>
            <option value="aardappelen" <?php echo ($edit_row['producttype'] == 'aardappelen') ? 'selected' : ''; ?>>Aardappelen</option>
            <option value="kaas" <?php echo ($edit_row['producttype'] == 'kaas') ? 'selected' : ''; ?>>Kaas</option>
            <option value="plantaardig en eiren" <?php echo ($edit_row['producttype'] == 'plantaardig en eiren') ? 'selected' : ''; ?>>Plantaardig en eiren</option>
            <option value="bakkerij en banket" <?php echo ($edit_row['producttype'] == 'bakkerij en banket') ? 'selected' : ''; ?>>Bakkerij en banket</option>
            <option value="frisdrank" <?php echo ($edit_row['producttype'] == 'frisdrank') ? 'selected' : ''; ?>>Frisdrank</option>
            <option value="sappen" <?php echo ($edit_row['producttype'] == 'sappen') ? 'selected' : ''; ?>>Sappen</option>
            <option value="koffie en thee" <?php echo ($edit_row['producttype'] == 'koffie en thee') ? 'selected' : ''; ?>>Koffie en thee</option>
            <option value="pasta" <?php echo ($edit_row['producttype'] == 'pasta') ? 'selected' : ''; ?>>pasta</option>
            <option value="rijst en wereldkeuken" <?php echo ($edit_row['producttype'] == 'rijst en wereldkeuken') ? 'selected' : ''; ?>>Rijst en wereldkeuken</option>
            <option value="soepen" <?php echo ($edit_row['producttype'] == 'soepen') ? 'selected' : ''; ?>>Soepen</option>
            <option value="sauzen" <?php echo ($edit_row['producttype'] == 'sauzen') ? 'selected' : ''; ?>>Sauzen</option>
            <option value="kruiden en olie" <?php echo ($edit_row['producttype'] == 'kruiden en olie') ? 'selected' : ''; ?>>Kruiden en olie</option>
            <option value="snoep" <?php echo ($edit_row['producttype'] == 'snoep') ? 'selected' : ''; ?>>Snoep</option>
            <option value="koek" <?php echo ($edit_row['producttype'] == 'koek') ? 'selected' : ''; ?>>Koek</option>
            <option value="chips en chocolade" <?php echo ($edit_row['producttype'] == 'chips en chocolade') ? 'selected' : ''; ?>>Chops en chocolade</option>
            <option value="baby" <?php echo ($edit_row['producttype'] == 'baby') ? 'selected' : ''; ?>>Baby</option>
            <option value="Verzorging en hygiene" <?php echo ($edit_row['producttype'] == 'Verzorging en hygiene') ? 'selected' : ''; ?>>verzorging en hygiene</option>
            <option value="overig" <?php echo ($edit_row['producttype'] == 'overig') ? 'selected' : ''; ?>>Overig</option>
            
        </select>
        <input type="text" name="allergieën" placeholder="Allergieën" value="<?php echo htmlspecialchars($edit_row['allergieën']); ?>" required>
        <input type="text" name="locatie" placeholder="Locatie" value="<?php echo htmlspecialchars($edit_row['locatie']); ?>" required>
        <input type="date" name="houdsbaarheidsdatum" placeholder="Houdsbaarheidsdatum" value="<?php echo htmlspecialchars($edit_row['houdsbaarheidsdatum']); ?>" required>
        <input type="text" name="streepjescode" placeholder="Streepjescode" value="<?php echo htmlspecialchars($edit_row['streepjescode']); ?>" required>
        <button type="submit" name="update_product">Bijwerken</button>
    </form>
<?php
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>

</body>
</html>