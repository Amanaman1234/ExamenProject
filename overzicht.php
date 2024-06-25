<?php include("header.php") ?>
    <title>Form</title>
    <link rel="stylesheet" href="css/overzicht.css">
</head>
<body>
    <form id="deliveryForm">
        <div class="form-group">
            <label for="categorie">Categorie</label>
            <input type="text" id="categorie" name="categorie">
        </div>
        <div class="form-group">
            <label for="leverancier">Leverancier</label>
            <input type="text" id="leverancier" name="leverancier">
        </div>
        <div class="form-group">
            <label for="jaar">Jaar</label>
            <input type="text" id="jaar" name="jaar">
        </div>
        <div class="form-group">
            <label for="maand">Maand</label>
            <input type="text" id="maand" name="maand">
        </div>
        <div class="form-group">
            <button type="submit">Submit</button>
        </div>
        <div class="result">
            <textarea id="resultText">hallo</textarea>
        </div>
    </form>
</body>
</html>
