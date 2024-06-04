<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/invetaris.css">
    <title>Document</title>
</head>
<body class=background>

<form method="GET" action="">
        <input type="text" name="search" placeholder="Zoek op streepjescode" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Zoeken</button>
</form>

<h2>Nieuw Product Toevoegen</h2>
    <form method="POST" action="">
        <input type="text" name="product" placeholder="Product" required>
        <input type="number" name="aantal" placeholder="Aantal" required>
        <select name="producttype" required>
            <option value="">Kies een producttype</option>
            <option value="groenten">Groenten</option>
            <option value="vlees">Vlees</option>
            <option value="fruit">Fruit</option>
            <option value="vis">Vis</option>
            <option value="pasta">Pasta</option>
        </select>
        <input type="text" name="locatie" placeholder="Locatie" required>
        <input type="date" name="houdsbaarheidsdatum" placeholder="Houdsbaarheidsdatum" required>
        <input type="text" name="streepjescode" placeholder="Streepjescode" required>
        <button type="submit" name="add_product">Toevoegen</button>
    </form>

<h1>Product Aantal Overzicht</h1>
        <table class=tabel border="1">
            <thead>
                <tr>
                    <th>ProductId</th>
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>ProductType</th>
                    <th>locatie</th>
                    <th>houdsbaarheidsdatum</th>
                    <th>streepjescode</th>
                </tr>
            </thead>
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
    $locatie = $conn->real_escape_string($_POST['locatie']);
    $houdsbaarheidsdatum = $conn->real_escape_string($_POST['houdsbaarheidsdatum']);
    $streepjescode = $conn->real_escape_string($_POST['streepjescode']);

    $insert_query = "INSERT INTO invetaris (product, aantal, producttype, locatie, houdsbaarheidsdatum, streepjescode) VALUES ('$product', '$aantal', '$producttype', '$locatie', '$houdsbaarheidsdatum', '$streepjescode')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Nieuw product succesvol toegevoegd!";
    } else {
        echo "Fout bij het toevoegen van het product: " . $conn->error;
    }
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM invetaris";
if (!empty($search)) {
    $query .= " WHERE streepjescode LIKE '%$search%'";
}
$query .= " GROUP BY invetaris.product, invetaris.aantal, invetaris.producttype, invetaris.locatie, invetaris.streepjescode, invetaris.houdsbaarheidsdatum";
                     
$result = $conn->query($query);
?>
            <tbody>
                <?php                    
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
                    echo "</tr>";
                }
                } else {
                    echo "<tr><td colspan='6'>Geen gegevens gevonden</td></tr>";
                }
                ?>
                
            </tbody>
        </table>
</body>
</html>