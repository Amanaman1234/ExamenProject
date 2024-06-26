<?php include("header.php") 

?>
    <title>Form</title>
    <link rel="stylesheet" href="css/overzicht.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <form id="deliveryForm">
        <div class="form-group">
            <label for="categorie">Categorie</label>
          
        </div>
        <div class="form-group">
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
            <input name="maand" type="month" id="maand"  value="<?php if(isset($_GET['maand'])){echo $_GET['maand']; } ?>" required>
        </div>
        <div class="form-group">
            <button name="submit" type="submit">Submit</button>
        </div>
    <table id="productTable" class="tabel display" border="1">
        <thead>
            <tr>
                <th>Categorie</td>
                <th>Product</td>
                <th>Aantal</td>
                <th>leveringsdatum</th>
                <th>Leverancier</td>
                <th>leveringsdatum</td>
            </tr>
        </thead>
        <tbody>
        <?php 

$servername= "localhost";
$username= "root";
$password="";
$dbname="examenvoedselbank";

$conn = mysqli_connect($servername,$username, $password, $dbname);

If(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}

if(isset($_GET["maand"])){
    $filtervaluescat = $_GET["categorie"];
    $filtervaluesmaand = $_GET["maand"];

    $query = "SELECT * FROM invetaris INNER JOIN leveranciers ON invetaris.leveringsdatum = leveranciers.leveringdatum 
    WHERE producttype LIKE '$filtervaluescat' AND leveringsdatum LIKE '$filtervaluesmaand%';";


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
                    <td><?= $row['volgendelevering']?></td>   
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
    </tr>

    </form>














    <form id="deliveryForm">
        <div class="form-group">
            <label for="postcode">postcode</label>
            <input name="postcode" type="text" id="postcode"  value="<?php if(isset($_GET['postcode'])){echo $_GET['postcode']; } ?>" required>
        </div>
        </div>

        <div class="form-group">
            <label for="maandvoorpc">Maand</label>
            <input name="maandvoorpc" type="month" id="maandvoorpc"  value="<?php if(isset($_GET['maandvoorpc'])){echo $_GET['maandvoorpc']; } ?>" required>
        </div>
        <div class="form-group">
            <button name="submit2" type="submit">Submit</button>
        </div>
    <table id="productTable" class="tabel display" border="1">
        <thead>
            <p>Excuus ik weet dat ik op postcode moet zoeken inplaats van klantnaam maar mika en danny hebben de tabel pakket producten een beetje fout geschreven en ik heb niet genoeg tijd om de databse te veranderen sorry daarvoor</p>
            <tr>
                <th>Postcode</td>
                <th>Naam</td>
                <th>Categorie</th>
                <th>aantal</th>
                <th>Uitgiftedatum</th>
            </tr>
        </thead>
        <tbody>
        <?php 


if(isset($_GET["postcode"])){
    $filtervaluepostcode = $_GET["postcode"];
    $filtervaluesmaandpc = $_GET["maandvoorpc"];

    $query = "SELECT *
FROM voedselpakketten
INNER JOIN pakket_producten ON voedselpakketten.pakketid = pakket_producten.pakketid
INNER JOIN klanten ON klanten.naam = voedselpakketten.klantnaam
WHERE klantnaam LIKE '$filtervaluepostcode'
  AND voedselpakketten.uitgiftedatum LIKE '$filtervaluesmaandpc%';
";


    $query_run = mysqli_query($conn, $query);


    if(mysqli_num_rows($query_run) > 0){
        foreach($query_run as $row){
            ?>
                <tr>
                    <td><?= $row['postcode']?></td>
                    <td><?= $row['naam']?></td>
                    <td><?= $row['producttype']?></td>
                    <td><?= $row['aantal']?></td>
                    <td><?= $row['uitgiftedatum']?></td>

            <?php
            
        }
    }else{ ?>
    <tr>
        <td colspan="5">No record found</td>
    </tr>
    <?php
    }
}

?>
        </tbody>
    </table>
    </tr>

    </form>
    
</body>


  



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>
</html>