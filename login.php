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

           <div id="Logo"></div>
           <h1><?php curTime(); ?></h1>
            <form action="include/login.inc.php" method="post">
                <input type="text" name="email" placeholder="e-mail">
                <input type="text" name="Wachtwoord" placeholder="Wachtwoord">
                <button type="submit" name="submit">Login</button>
            </form>
           </section>
           <section class="rechts"><img id="goku" src="./img/Blauuw.png"></section>
        </section>
    </main>
</body>
</html>
