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