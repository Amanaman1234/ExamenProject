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

    $query = "SELECT * FROM invetaris INNER JOIN leveranciers ON invetaris.leveringsdatum = leveranciers.leveringdatum WHERE producttype LIKE '$filtervaluescat' AND leveringsdatum LIKE '$filtervaluesmaand%';";


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
        <td colspan="3">No record found</td>
    </tr>
    <?php
    }
}

?>
        </tbody>
    </table>
</body>

</tr>

    </form>
  



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>
</html>
<input name="categorie" type="text"  id="categorie">
<th>naam Leverancier</th>
<th>Datum geleeverd</th>