<?php include("header.php") 
?>
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



<h1>Maandelijkse Overzicht</h1>
<table id="productTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>ProductId</th>
            <th>Product</th>
            <th>Aantal</th>
            <th>ProductType</th>
            <th>locatie</th>
            <th>houdsbaarheidsdatum</th>
            <th>streepjescode</th>
            <th>bewerken</th>
        </tr>
    </thead>
    <tbody>
        <?php
            require_once 'include/dbh.php';
            require_once 'include/functions.php';



        if (isset($_POST['update_product'])) {
            $productid = $conn->real_escape_string($_POST['productid']);
            $product = $conn->real_escape_string($_POST['product']);
            $aantal = $conn->real_escape_string($_POST['aantal']);
            $producttype = $conn->real_escape_string($_POST['producttype']);
            $locatie = $conn->real_escape_string($_POST['locatie']);
            $houdsbaarheidsdatum = $conn->real_escape_string($_POST['houdsbaarheidsdatum']);
            $streepjescode = $conn->real_escape_string($_POST['streepjescode']);

            $update_query = "UPDATE invetaris SET product='$product', aantal='$aantal', producttype='$producttype', locatie='$locatie', houdsbaarheidsdatum='$houdsbaarheidsdatum', streepjescode='$streepjescode' WHERE productid='$productid'";

            if ($conn->query($update_query) === TRUE) {
                echo "";
            } else {
                echo "Fout bij het bijwerken van het product: " . $conn->error;
            }
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
                echo "<td>" . htmlspecialchars($row['productid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['product']) . "</td>";
                echo "<td>" . htmlspecialchars($row['aantal']) . "</td>";
                echo "<td>" . htmlspecialchars($row['producttype']) . "</td>";
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
        <input type="hidden" name="productid" value="<?php echo htmlspecialchars($edit_row['productid']); ?>">
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