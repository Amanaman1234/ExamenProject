<?php include("header.php") ?>
    <title>Form</title>
    <link rel="stylesheet" href="css/overzicht.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <form id="deliveryForm">
        <div class="form-group">
            <label for="categorie">Categorie</label>
            <input name="categorie" type="text" value="<?php if(isset($_GET['categorie'])){echo $_GET['categorie']; } ?>" id="categorie">
        </div>
        <div class="form-group">
            <label for="leverancier">Leverancier</label>
            <input name="leverancier" type="text" value="<?php if(isset($_GET['leverancier'])){echo $_GET['leverancier']; } ?>" id="leverancier">
        </div>
        <div class="form-group">
            <label for="jaar">Jaar</label>
            <input name="jaar" type="text" id="jaar" name="jaar">
        </div>
        <div class="form-group">
            <label for="maand">Maand</label>
            <input name="maand" type="text" id="maand" name="maand">
        </div>
        <div class="form-group">
            <button name="submit" type="submit">Submit</button>
        </div>
        <div class="result">
            <textarea name="resultaat" id="resultText"></textarea>
        </div>
    </form>
    <table id="productTable" class="tabel display" border="1">
        <thead>
            <tr>
                <th>Id</td>
                <th>Product</td>
                <th>Aantal</td>

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

if(isset($_GET["categorie"])){
    $filtervaluescat = $_GET["categorie"];
    $filtervaluesleev = $_GET["leverancier"];

    $query = "SELECT * FROM invetaris WHERE CONCAT(productid,product,aantal) LIKE '%$filtervaluescat%'  ";


    $query_run = mysqli_query($conn, $query);


    if(mysqli_num_rows($query_run) > 0){
        foreach($query_run as $row){
            ?>
                <tr>
                    <td><?= $row['productid']?></td>
                    <td><?= $row['product']?></td>
                    <td><?= $row['aantal']?></td>
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



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>
</html>
