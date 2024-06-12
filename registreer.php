
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
            <h1>Registreer hier :</h1>

            <form action="include/registreer.inc.php" method="post">
                <input type="text" name="VoorNaam" placeholder="VoorNaam">
                <input type="text" name="AchterNaam" placeholder="AchterNaam">`
                <input type="text" name="Tussenvoegsels" placeholder="Tussenvoegsels">
                <input type="text" name="email" placeholder="e-mail">
                <input type="text" name="Wachtwoord" placeholder="Wachtwoord">
                <input type="text" name="HerhaalWachtwoord" placeholder="Herhaal Wachtwoord">
                <button type="submit" name="submit">Registreer</button>
            </form>
           </section>
           <section class="rechts"></section>
        </section>
    </main>
</body>
</html>

