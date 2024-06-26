<?php include("header.php") ?>
<link rel="stylesheet" href="css/registreer.css">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreer</title>
</head>
<body> 
    <main class="main">
        <section class="form">
           <section class="links">
            <h1>Verander Wachtwoord</h1>
            <form action="include/Changepw.inc.php" method="post">
                <input type="text" name="OudeWachtwoord" placeholder="Oude wachtwoord">
                <input type="text" name="NieuwWachtwoord" placeholder="NieuwWachtwoord">
                <input type="text" name="NieuwWachtwoordRep" placeholder="Herhaal nieuw wachtwoord">
                <button type="submit" name="submit">Verander Wachtwoord</button>
            </form>
           </section>
           <section class="rechts"></section>
        </section>
    </main>
</body>
</html>
