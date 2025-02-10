<?php
// Includi il file di configurazione della sessione (deve essere il primo)
include('private/session_config.php');
$pageTitle = "Homepage";
include("includes/header.php");

if (isset($_SESSION['username'])) {
    echo "<h2>Benvenuto, " . $_SESSION['username'] . "!</h2>";
    echo "<p>Questa è la tua homepage personale.</p>";
    echo "<a href='safepage.php'>Pagina sicura</a><br><br>";
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
        # file Database in private area not connected with public files for UE and UI == SET private on .HTaccess "deny for all"
            .htaccess Deny from all ==> test http://localhost/private/db.php). Dovresti ricevere un errore "403 Forbidden"
        #SQLinjection (mysqli improvement && PDO PHP data objects)
        #session (sicurezza lifetime cookie || only HTTP access to cookie, NO Js || Cookie solo se sito HTTPS || reject ID session unvalid )
            disabilitati durante la realizzazione del sito per evitare blocchi https e http
            session fixation con  session_regenerate_id(true); dopo il login
        #hashing && verifica password == password_hash()  l'algoritmo di hashing (Bcrypt) e password_verify()
            Brute force check log (double block based on ip 5m + 30m)
        #protection attack CSRF(Cross-Site Request Forgery) e verifica token CSRF
            User inizia sessione == si genere token di sessione CSRF inviato crittografato con campo nascosco nel form HTML (login o registrazione)
            quando server riceve richieste lo confronta con il token di sessione, se matchano ok altrimenti rifiuta la richiesta POST PUT DELETE
            per cambi dati utente, registrazioni, eliminazione account, modifiche carrello, pagamenti
        #Protezione XSS (Cross-Site Scripting) -- sanificazione input
            sanificare dati input EVITA iniettare script maligni per
            accesso sessini, invio dati personali.
            Proteggere post get
            sanificare codice HTML e JS con htmlspecialchars() htmlentities()
            codifica INPUT OUTPUT
            Ogni volta che si stampa un input va trattato codificandolo
            per leggerlo solo come testo e non codice JS o html
             htmlspecialchars() per la maggior parte delle situazioni (ad esempio, quando inserisci dati in HTML).
            Usa htmlentities() quando è necessario codificare più caratteri speciali (ad esempio, se hai a che fare con testi in lingue diverse o simboli particolari).
                devi specificare il tipo codifica tipo utf-8, servirebbe se dovessi rappresentare in testo caratteri speciali, tipo un editor online di codice.
                Se nei commenti usano codice puoi usare entities per renderlo leggibile usando
                //mostra commento come testo normale, non esegue script
                $user_comment = "<script>alert('Hacked!');</script>";
                $sanitized_comment = htmlspecialchars($user_comment, ENT_QUOTES, 'UTF-8');
                echo "<p>Commento: $sanitized_comment</p>";
                //scrive il codice dentro il tag code, non interpretato da HTML
                $user_code = "<h1>Title</h1>";
                $sanitized_code = htmlentities($user_code, ENT_QUOTES, 'UTF-8');
                echo "<pre><code>$sanitized_code</code></pre>";


      3 - Offuscamento codice JavaScript = impossibile copiare codice / difficile debugging vulnerabilità / evita modifiche lato client validazioni form/ codice / estrazione API keys hardcoded
            TECNICHE OFFUSCAMENTO == risoluzione con js-beautify o deobfuscator.io. decifra codice || aumenta peso codice  || debugging da incubo
                1- minificazione: rimuove spazi/commenti riduce nome variabili varibileNome = e
                2- Offuscamento var && function == nomi chiave == x1a
                3- string encoding == crypta stringhe testo == \x48\x41\x78\x6F
                4- Eval & SelfExecutingCode eval() autoeseguibile rende più complesso il codice
                5- Polimorfismo == genera versioni di codice diverse ad ogni deploy
                6- Astrazione logica == incapsula funzioni in strutture complesse
                🔐 Strumenti per offuscare JavaScript
                    
                    Se vuoi applicare l’offuscamento senza farlo manualmente, puoi usare strumenti specifici:
                        UglifyJS (https://github.com/mishoo/UglifyJS) – Per minificare e offuscare.
                        JavaScript Obfuscator (https://obfuscator.io/) – Un tool online per offuscare codice.
                        Terser (https://terser.org/) – Alternativa moderna a UglifyJS.
                        Google Closure Compiler – Ottimizza e offusca il codice per performance migliori.

      4 - Fingerprinting del browser == similare A captcha V3 analizza comportamento Utente VS bot
                            NON implementato al momentom perchè richiede implementazioni IA
      5 - Generazione di token dinamici (lato server) fatto
      6 - Rate Limiting (limite di richieste per IP,) parzialmente fatto si può apliare a reset password e modifiche, API,  limiti htaccess, mod_security
      7 - Protezione contro scraping (bloccare bot)
                            A Implementa CAPTCHA nelle aree sensibili come il login e la registrazione.
                                implementare captcha di controllo dentro i contenuti per limitare bots
                            B RATE LIMITING Limita il numero di richieste per evitare il rate limiting.
                                -tramite server controlla richieste per sessione/tempo
                                -Nginx di php server web, file nginx.conf == limite richieste per IP
                                -API nodejs usa libreria esterna
                                
                            C Controlla User-Agent per identificare bot.
                                UserAgent == Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36
                                    Mozilla è un residuo storico, fa parte della stringa
                                    OS + Architettura
                                    motore rendering Browser
                                    versione browser (chrome in questo esempio)
                                    safari incluso per compatibilità
                                    SERVE A serverWeb ==
                                        identificare dispositivo && browser (ottimizzando contenuto)
                                    Googlebot  crawler == Googlebot/2.1 (+http://www.google.com/bot.html)
                                    Bingbot == Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36 Edg/91.0.864.67
                                    Scrapy py framework == scrapy/2.4.0 (+https://scrapy.org)
                                Identifica facilmente se inviano stringhe user agent distintive
                                    VERIFICHE:
                                        -Manuale == se UserAgent contiene keyWord (bot, crawler, spider)
                                        -Whitelist == lista di useragent noti
                                        -Blacklist == blocco UA sospetti
                                        -Requesttax == rapide e numerose richieste, indica bot
                                        -IPverify == se IP sospetto fa troppe richieste
                                        
                            D Imposta il file robots.txt per indirizzare i motori di ricerca.
                            E Rileva comportamenti sospetti e blocca gli accessi anomali.
                            F Usa JavaScript dinamico per evitare che i bot ottengano contenuti statici.
      8 - Implementazione CAPTCHA (reCAPTCHA/hCaptcha)
                            implementato Googlev2 php.ini extension=openssl, extension=curl, extension=mbstring
                            copia la risposta con chiave segreta in private per proteggere il processo
                            costante define == secret key captcha
                            LINUX || WINDOWS
                            NEXT LEVEL di sicurezza crea file .bashrc || .bash_profile || profile
                            in base a system config, gestisci variabili d'ambiente e Key API, evita
                            che key vengano hardcodate nel codice, separate da codice applicativo
                            .bashrc => export RECAPTCHA_SECRET_KEY="6LeNDcoqAAAAAGxnZBAE44lwFXjfSHbm84LtpvSP"
                            nel codice $secret_key = getenv('RECAPTCHA_SECRET_KEY');
                            questo solo su server LINUX, per WIN è diverso forse file ENV, usa gitignore per tutelarli
      9 - Prevenzione CSRF (parzialmente applicato sui token, applicabile anche ai JWT con json e API )
      10 - Configurazione Reverse Proxy (Cloudflare)
      11 - CDN e protezione DDoS (Cloudflare o simili)
      12 - Penetration Testing (simulazione attacchi)

      SIMULATION ATTACKS...
    -->

<body>
    <div class="container">
        <h1>Welcome - Safe Area !</h1>
        <p>Prototype = test level di sicurezza e meccaniche di reindirizzamento.</p>

        <button id="redirectBtn" class="btn">Test btn Reindirizzamento</button>
    </div>
</body>
</html>
