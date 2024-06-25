<?php
 session_start();
 require_once 'include/functions.php';
?>
<link rel="stylesheet" href="css/login.css">


<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body> 
    <main class="main">

        <section class="form">
           <section class="links">

           <div id="Logo"><img src="img/voedselbank-velsen11.png" id="logo_img">Voedselbank Almere</div>
           <h1> <?php curTime(); ?> <br> Welkom! </h1>


            <form   action="include/login.inc.php" method="post">
                <input type="text" name="email" placeholder="E-mail Adress ">
                <input type="text" name="Wachtwoord" placeholder="Wachtwoord">
                <div class="buttons">
                    <button type="submit" name="submit" class="login">Login</button>
                </div>
            </form>

           </section>
           <section class="rechts"><img id="goku" src="./img/Blauuw.png"></section>
        </section>
    </main>
</body>
</html>
