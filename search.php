<?php
require_once 'include/dbh.php';

// Haal de zoekterm op uit de URL
$term = $_GET['term'];

// Bereid de SQL-query voor om klanten op te halen wiens naam de zoekterm bevat
$klanten_query = "SELECT naam, gezingroote, leeftijd, allergieën, voorkeuren FROM klanten WHERE naam LIKE '%$term%'";

// Voer de query uit en sla het resultaat op in $klanten_result
$klanten_result = $conn->query($klanten_query);

// Voeg de rij toe aan de $klanten array
$klanten = [];

// Controleer of er rijen zijn opgehaald
if ($klanten_result->num_rows > 0) {
    while ($row = $klanten_result->fetch_assoc()) {
        $klanten[] = $row;
    }
}

// Encodeer de $klanten array als JSON en echo het resultaat
echo json_encode($klanten);

$conn->close();
?>
