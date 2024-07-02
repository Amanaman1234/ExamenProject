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

            <h1>Registreer hier een medewerker:</h1>

            <form action="include/registreer.inc.php" method="post">

                <input type="text" name="VoorNaam" placeholder="VoorNaam">

                <input type="text" name="AchterNaam" placeholder="AchterNaam">

                <input type="text" name="Tussenvoegsels" placeholder="Tussenvoegsels">

                <input type="text" name="email" placeholder="e-mail">

                <input type="text" name="Wachtwoord" placeholder="Wachtwoord">

                <input type="text" name="HerhaalWachtwoord" placeholder="Herhaal Wachtwoord">

                <select  name="Positie" placeholder="positie">
                    <option value="medewerker">medewerker</option>
                    <option value="vrijwilliger">Vrijwilliger</option>
                    <option value="directie">directie</option>
                             </select>
                <button type="submit" name="submit">Registreer</button>
                <?php   require_once 'include/errortext.php';?>
            </form>
        </section>
    </main>
</body>
</html>

