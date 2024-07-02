<?php include ("header.php") ?>
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

    if (isset($_POST['add_package'])) {
        $klantnaam = $conn->real_escape_string($_POST['naam']);
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
    $klanten_query = "SELECT naam, leeftijd_onder_2, leeftijd_2_tot_18, leeftijd_boven_18, allergieën, voorkeuren FROM klanten";
    $klanten_result = $conn->query($klanten_query);
    ?>
    <form id="filter-form" method="POST">
        <div class="search-container">
            <input type="text" id="search-bar" class="search-bar" placeholder="zoek een naam...">
            <input type="hidden" name="naam" id="selectedid">
            <div id="search-results" class="search-results"></div>
        </div>
        <div id="klantInfo"></div>
        <div id="productContainer">
            <div class="productEntry">
                <div class="search-container">
                    <input type="text" id="search-bar2" class="search-bar" placeholder="zoek een product...">
                    <input type="hidden" name="productid[]" id="selectedProductid">
                    <div id="search-results2" class="search-results"></div>
                </div>
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
        const products = [
            <?php
            $product_query = "SELECT productid, product, aantal FROM invetaris";
            $product_result = $conn->query($product_query);
            if ($product_result->num_rows > 0) {
                while ($product_row = $product_result->fetch_assoc()) {
                    echo "{ id: " . json_encode($product_row['productid']) . ", name: " . json_encode($product_row['product']) . ", aantal: " . json_encode($product_row['aantal']) . " },";
                }
            }
            ?>
        ];

        function initializeProductSearch(searchBar, resultsContainer) {
            searchBar.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                resultsContainer.innerHTML = '';
                products.forEach(product => {
                    if (product.name.toLowerCase().includes(query)) {
                        const resultItem = document.createElement('div');
                        resultItem.classList.add('search-result-item');
                        resultItem.textContent = `${product.name} (Beschikbaar: ${product.aantal})`;
                        resultItem.dataset.id = product.id;
                        resultItem.addEventListener('click', function() {
                            searchBar.value = product.name;
                            resultsContainer.previousElementSibling.value = product.id;
                            resultsContainer.innerHTML = '';
                        });
                        resultsContainer.appendChild(resultItem);
                    }
                });
            });
        }

        function loadProductData() {
            const initialSearchBar = document.getElementById('search-bar2');
            const initialResultsContainer = document.getElementById('search-results2');
            initializeProductSearch(initialSearchBar, initialResultsContainer);
        }

        function addProduct() {
            const productContainer = document.getElementById('productContainer');
            const newProductEntry = document.createElement('div');
            newProductEntry.classList.add('productEntry');
            newProductEntry.innerHTML = `
                <div class="search-container">
                    <input type="text" class="search-bar" placeholder="zoek een product...">
                    <input type="hidden" name="productid[]">
                    <div class="search-results"></div>
                </div>
                <input type="number" name="productaantal[]" placeholder="Aantal" required>
            `;
            productContainer.appendChild(newProductEntry);

            const newSearchBar = newProductEntry.querySelector('.search-bar');
            const newResultsContainer = newProductEntry.querySelector('.search-results');
            initializeProductSearch(newSearchBar, newResultsContainer);
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadClientData();
            loadProductData();
        });

        function loadClientData() {
            const clients = [
                <?php
                if ($klanten_result->num_rows > 0) {
                    while ($klanten_row = $klanten_result->fetch_assoc()) {
                        echo "{ naam: " . json_encode($klanten_row['naam']) . ", leeftijd_onder_2: " . json_encode($klanten_row['leeftijd_onder_2']) . ", leeftijd_2_tot_18: " . json_encode($klanten_row['leeftijd_2_tot_18']) . ", leeftijd_boven_18: " . json_encode($klanten_row['leeftijd_boven_18']) . ", allergieën: " . json_encode($klanten_row['allergieën']) . ", voorkeuren: " . json_encode($klanten_row['voorkeuren']) . " },";
                    }
                }
                ?>
            ];

            const searchBar = document.getElementById('search-bar');
            const resultsContainer = document.getElementById('search-results');
            const klantInfo = document.getElementById('klantInfo');

            searchBar.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                resultsContainer.innerHTML = '';
                clients.forEach(client => {
                    if (client.naam.toLowerCase().includes(query)) {
                        const resultItem = document.createElement('div');
                        resultItem.classList.add('search-result-item');
                        resultItem.textContent = client.naam;
                        resultItem.addEventListener('click', function() {
                            searchBar.value = client.naam;
                            resultsContainer.innerHTML = '';
                            document.getElementById('selectedid').value = client.naam;

                            klantInfo.innerHTML = `
                                <p><strong>Leeftijd onder 2:</strong> ${client.leeftijd_onder_2}</p>
                                <p><strong>Leeftijd 2 tot 18:</strong> ${client.leeftijd_2_tot_18}</p>
                                <p><strong>Leeftijd boven 18:</strong> ${client.leeftijd_boven_18}</p>
                                <p><strong>Allergieën:</strong> ${client.allergieën}</p>
                                <p><strong>Voorkeuren:</strong> ${client.voorkeuren}</p>
                            `;
                        });
                        resultsContainer.appendChild(resultItem);
                    }
                });
            });
        }

        $(document).ready(function() {
            $('#packageTable').DataTable();
        });
    </script>

    <?php $conn->close(); ?>
</body>
</html>
