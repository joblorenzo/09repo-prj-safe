<?php
session_start(); // Avvia la sessione

$pageTitle = "Homepage";

include("includes/header.php");

if (isset($_SESSION['username'])) {
    echo "<h2>Benvenuto, " . $_SESSION['username'] . "!</h2>";
    echo "<p>Questa è la tua homepage personale.</p>";
    echo "<a href='logout.php'>Esci</a>";
} else {
    echo "<h2>Benvenuto alla Homepage</h2>";
    echo "<p>Per accedere, <a href='login.php'>clicca qui</a>.</p>";
}

include("includes/footer.php");
?>
    <!--
      1 - Creazione della pagina base (HTML, CSS, JavaScript)
        # php -S localhost:8000 (dinamico)
        # py -m http.server 8001 --bind 127.0.0.1 (static)
      2 - Reindirizzamenti (client-side e server-side con PHP)
        # file Database in private area not connected with public files for UE and UI == SET private on HTaccess deny for all
            .htaccess Deny from all ==> test http://localhost/private/db.php). Dovresti ricevere un errore "403 Forbidden"
        #SQLinjection (mysqli improvement && PDO PHP data objects)
        #session (sicurezza lifetime cookie || only HTTP access to cookie, NO Js || Cookie solo se sito HTTPS || reject ID session unvalid )
        #hashing && verifica password == password_hash()  l'algoritmo di hashing (Bcrypt) e password_verify()
        #protection attack CSRF e verifica token CSRF
        #Protezione XSS (Cross-Site Scripting) -- sanificazione input

      3 - Offuscamento codice JavaScript
      4 - Fingerprinting del browser
      5 - Generazione di token dinamici (lato server)
      6 - Rate Limiting (limite di richieste per IP)
      7 - Protezione contro scraping (bloccare bot)
      8 - Implementazione CAPTCHA (reCAPTCHA/hCaptcha)
      9 - Prevenzione CSRF
      10 - Configurazione Reverse Proxy (Cloudflare)
      11 - CDN e protezione DDoS (Cloudflare o simili)
      12 - Penetration Testing (simulazione attacchi)
    -->

<body>
    <div class="container">
        <h1>Welcome - Safe Area !</h1>
        <p>Prototype = test level di sicurezza e meccaniche di reindirizzamento.</p>

        <button id="redirectBtn" class="btn">Test btn Reindirizzamento</button>
    </div>
</body>
</html>
