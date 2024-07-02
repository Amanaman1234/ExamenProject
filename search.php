<?php
require_once 'include/dbh.php';

$term = $_GET['term'];
$klanten_query = "SELECT naam, gezingroote, leeftijd, allergieën, voorkeuren FROM klanten WHERE naam LIKE '%$term%'";
$klanten_result = $conn->query($klanten_query);

$klanten = [];
if ($klanten_result->num_rows > 0) {
    while ($row = $klanten_result->fetch_assoc()) {
        $klanten[] = $row;
    }
}
echo json_encode($klanten);

$conn->close();
?>
