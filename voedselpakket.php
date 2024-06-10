<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/voedselpakket.css">
    <title>Voedselpakket Samenstellen</title>
</head>
<body class="background">

<h2>Nieuw Voedselpakket Samenstellen</h2>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examenvoedselbank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_package'])) {
    $klantnaam = $conn->real_escape_string($_POST['klantnaam']);
    $samenstellingsdatum = $conn->real_escape_string($_POST['samenstellingsdatum']);
    $uitgiftedatum = $conn->real_escape_string($_POST['uitgiftedatum']);
    $product_ids = $_POST['productid'];
    $product_aantallen = $_POST['productaantal'];
    $error = false;

    foreach ($product_ids as $index => $productid) {
        $productid = $conn->real_escape_string($productid);
        $productaantal = $conn->real_escape_string($product_aantallen[$index]);
        $inventory_check_query = "SELECT product, aantal FROM invetaris WHERE productid = '$productid'";
        $inventory_check_result = $conn->query($inventory_check_query);

        if ($inventory_check_result->num_rows > 0) {
            $inventory_row = $inventory_check_result->fetch_assoc();
            if ($inventory_row['aantal'] < $productaantal) {
                $error = true;
                echo "<p style='color: red;'>Niet genoeg voorraad voor product: " . htmlspecialchars($inventory_row['product']) . ". Beschikbaar: " . htmlspecialchars($inventory_row['aantal']) . ", Gevraagd: " . htmlspecialchars($productaantal) . "</p>";
                break;
            }
        } else {
            $error = true;
            echo "<p style='color: red;'>Product ID: " . htmlspecialchars($productid) . " niet gevonden in inventaris.</p>";
            break;
        }
    }

    if (!$error) {
        $insert_package_query = "INSERT INTO voedselpakketten (klantnaam, samenstellingsdatum, uitgiftedatum) VALUES ('$klantnaam', '$samenstellingsdatum', '$uitgiftedatum')";
        if ($conn->query($insert_package_query) === TRUE) {
            $pakketid = $conn->insert_id;

            foreach ($product_ids as $index => $productid) {
                $productid = $conn->real_escape_string($productid);
                $productaantal = $conn->real_escape_string($product_aantallen[$index]);

                $insert_product_query = "INSERT INTO pakket_producten (pakketid, productid, aantal) VALUES ('$pakketid', '$productid', '$productaantal')";
                if ($conn->query($insert_product_query) !== TRUE) {
                    echo "Fout bij het toevoegen van het product: " . $conn->error;
                    $error = true;
                    break;
                }

                $update_inventory_query = "UPDATE invetaris SET aantal = aantal - '$productaantal' WHERE productid = '$productid'";
                if ($conn->query($update_inventory_query) !== TRUE) {
                    echo "Fout bij het bijwerken van de inventaris: " . $conn->error;
                    $error = true;
                    break;
                }
            }
            if ($error) {
                $conn->query("DELETE FROM voedselpakketten WHERE pakketid = '$pakketid'");
                $conn->query("DELETE FROM pakket_producten WHERE pakketid = '$pakketid'");
                echo "<p style='color: red;'>Er is een fout opgetreden. Probeer het opnieuw.</p>";
            }
        } else {
            echo "Fout bij het toevoegen van het pakket: " . $conn->error;
        }
    }
}
?>
<form method="POST" action="">
    <input type="text" name="klantnaam" placeholder="Klantnaam" required>
    <div id="productContainer">
        <div class="productEntry">
            <select name="productid[]" required>
                <option value="">Kies een product</option>
                <?php
                $product_query = "SELECT productid, product, aantal FROM invetaris";
                $product_result = $conn->query($product_query);

                if ($product_result->num_rows > 0) {
                    while ($row = $product_result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['productid']) . "'>" . htmlspecialchars($row['product']) . " (Beschikbaar: " . htmlspecialchars($row['aantal']) . ")</option>";
                    }
                } else {
                    echo "<option value=''>Geen producten beschikbaar</option>";
                }
                ?>
            </select>
            <input type="number" name="productaantal[]" placeholder="Aantal" required>
        </div>
    </div>
    <button type="button" onclick="addProduct()">Product Toevoegen</button>
    <br>
    <label for="samenstellingsdatum">Samenstellingsdatum:</label>
    <input type="date" name="samenstellingsdatum" required>
    <label for="uitgiftedatum">Uitgiftedatum:</label>
    <input type="date" name="uitgiftedatum" required>
    <button type="submit" name="add_package">Toevoegen</button>
</form>

<h1>Voedselpakketten Overzicht</h1>
<table id="packageTable" class="tabel display" border="1">
    <thead>
        <tr>
            <th>PakketId</th>
            <th>Klantnaam</th>
            <th>Producten</th>
            <th>Samenstellingsdatum</th>
            <th>Uitgiftedatum</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $package_query = "SELECT v.pakketid, v.klantnaam, v.samenstellingsdatum, v.uitgiftedatum, GROUP_CONCAT(CONCAT(i.product, ' (', p.aantal, ')') SEPARATOR ', ') AS producten 
                          FROM voedselpakketten v 
                          JOIN pakket_producten p ON v.pakketid = p.pakketid 
                          JOIN invetaris i ON p.productid = i.productid 
                          GROUP BY v.pakketid, v.klantnaam, v.samenstellingsdatum, v.uitgiftedatum";
        $package_result = $conn->query($package_query);

        if ($package_result->num_rows > 0) {
            while ($row = $package_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['pakketid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['klantnaam']) . "</td>";
                echo "<td>" . htmlspecialchars($row['producten']) . "</td>";
                echo "<td>" . htmlspecialchars($row['samenstellingsdatum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uitgiftedatum']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Geen gegevens gevonden</td></tr>";
        }
        ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#packageTable').DataTable();
    });

    function addProduct() {
        var container = document.getElementById('productContainer');
        var entry = document.createElement('div');
        entry.className = 'productEntry';
        entry.innerHTML = `
            <select name="productid[]" required>
                <option value="">Kies een product</option>
                <?php
                $product_result->data_seek(0); 
                while ($row = $product_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['productid']) . "'>" . htmlspecialchars($row['product']) . " (Beschikbaar: " . htmlspecialchars($row['aantal']) . ")</option>";
                }
                ?>
            </select>
            <input type="number" name="productaantal[]" placeholder="Aantal" required>
        `;
        container.appendChild(entry);
    }
</script>

</body>
</html>