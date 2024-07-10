<?php include("header.php");
checkaccesdirectie();
?>
    <title>Form</title>
    <link rel="stylesheet" href="css/overzicht.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <form id="deliveryForm1">

        <div class="form-group">
        <label for="categorie">Categorie</label>
        <select value="<?php if(isset($_GET['categorie'])){echo $_GET['categorie']; } ?>" name="categorie" required>
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
        </div>

        <div class="form-group">
            <label for="maand">Maand</label>
            <input name="maand" type="month" id="maand"  value="<?php if(isset($_GET['maand'])){echo $_GET['maand']; } ?>" >
        </div>
        <div class="form-group">
        <label for="jaar">Jaar</label>
            <select value="<?php if(isset($_GET['jaar'])){echo $_GET['jaar']; } ?>" name="jaar" >
            <option value="">Kies jaar</option>
            <?php
            date_default_timezone_set('Europe/Amsterdam');
            $curYear = date("Y");
            $year = 2024;

            for ($i = $curYear; $i >= $year; $i--) { 
                echo "<option value = '$i'>$i</option>";
            }
            ?>
            </select>
        </div>
        <div class="form-group">
            <button name="submit" type="submit">Check</button>
        </div>
    <table id="productTable1" class="tabel display" border="1">
        <thead>
            <tr>
                <th>Categorie</th>
                <th>Product</th>
                <th>Aantal</th>
                <th>Leveringsdatum</th>
                <th>Leverancier</th>
            </tr>
        </thead>
        <tbody>
        <?php 
if(isset($_GET["categorie"])){
    $filtervaluescat = $_GET["categorie"];
    if(isset($_GET["maand"]) && $_GET['maand'] !== ""){
        $filtervaluesmaand = $_GET["maand"];
    }elseif(isset($_GET["jaar"]) && $_GET['jaar'] !== ""){
        $filtervaluesmaand = $_GET["jaar"];
    }
    $query = "SELECT * FROM invetaris 
LEFT JOIN leveranciers 
ON invetaris.leveringsdatum = leveranciers.leveringdatum
WHERE producttype = '$filtervaluescat' 
AND invetaris.leveringsdatum LIKE '$filtervaluesmaand%'";
    $query_run = mysqli_query($conn, $query);
    if(mysqli_num_rows($query_run) > 0){
        foreach($query_run as $row){
            ?>
                <tr>
                    <td><?= $row['producttype']?></td>
                    <td><?= $row['product']?></td>
                    <td><?= $row['aantal']?></td>
                    <td><?= $row['leveringsdatum']; ?></td>     
                    <td><?= $row['contactpersoon']?></td>
                </tr>
            <?php
        }
    }else{ ?>
    <tr>
        <td colspan="6">No record found</td>
    </tr>
    <?php
    }
}
?>
        </tbody>
    </table>
    </form>

    <form id="deliveryForm2">
        <div class="form-group">
            <label for="postcode">Postcode</label>
            <input name="postcode" type="text" id="postcode"  value="<?php if(isset($_GET['postcode'])){echo $_GET['postcode']; } ?>" required>
        </div>
        <div class="form-group">
            <label for="maandvoorpc">Maand</label>
            <input name="maandvoorpc" type="month" id="maandvoorpc"  value="<?php if(isset($_GET['maandvoorpc'])){echo $_GET['maandvoorpc']; } ?>" >
        </div>
        <div class="form-group">
            <label for="jaarvoorpc">Jaar</label>
            <select value="<?php if(isset($_GET['jaarvoorpc'])){echo $_GET['jaarvoorpc']; } ?>" name="jaarvoorpc" >
            <option value="">Kies jaar</option>
            <?php
            date_default_timezone_set('Europe/Amsterdam');
            $curYear = date("Y");
            $year = 2024;
            for ($i = $curYear; $i >= $year; $i--) { 
                echo "<option value = '$i'>$i</option>";
            }
            ?>
            </select>
        </div>
        <div class="form-group">
            <button name="submit2" type="submit">Submit</button>
        </div>
    <table id="productTable2" class="tabel display" border="1">
        <thead>
            <tr>
                <th>Postcode</th>
                <th>Naam</th>
                <th>Categorie</th>
                <th>Producten</th>
                <th>Aantal</th>
                <th>Uitgiftedatum</th>
            </tr>
        </thead>
        <tbody>
        <?php 
if(isset($_GET["postcode"])){
    $filtervaluepostcode = $_GET["postcode"];
    if(isset($_GET["maandvoorpc"]) && $_GET['maandvoorpc'] !== ""){
        $filtervaluesmaandpc = $_GET["maandvoorpc"];
    }elseif(isset($_GET["jaarvoorpc"]) && $_GET['jaarvoorpc'] !== ""){
        $filtervaluesmaandpc = $_GET["jaarvoorpc"];
    }
    $query = "SELECT *, voedselpakketten.uitgiftedatum AS v_uitgiftdatum FROM voedselpakketten
INNER JOIN pakket_producten ON voedselpakketten.pakketid = pakket_producten.pakketid
INNER JOIN klanten ON klanten.naam = voedselpakketten.klantnaam 
INNER JOIN invetaris ON invetaris.productid = pakket_producten.productid
WHERE postcode LIKE '$filtervaluepostcode' AND voedselpakketten.uitgiftedatum LIKE '$filtervaluesmaandpc%'";
    $query_run = mysqli_query($conn, $query);
    if(mysqli_num_rows($query_run) > 0){
        foreach($query_run as $row){
            ?>
                <tr>
                    <td><?= $row['postcode']?></td>
                    <td><?= $row['naam']?></td>
                    <td><?= $row['producttype']?></td>
                    <td><?= $row['product']?></td>
                    <td><?= $row['aantalp']?></td>
                    <td><?= $row['v_uitgiftdatum']?></td>
                </tr>
            <?php
        }
    }else{ ?>
    <tr>
        <td colspan="6">No record found</td>
    </tr>
    <?php
    }
}
?>
        </tbody>
    </table>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable1').DataTable();
        $('#productTable2').DataTable();
    });
</script>
</html>
