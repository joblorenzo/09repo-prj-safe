<?php
// Includi i file necessari
include('private/session_config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      die("Errore CSRF: richiesta non valida.");
  }
}

$pageTitle = "Login";
include("includes/header.php");
include("private/db.php"); // Connessione al database
include("private/config.php"); //captcha secret key
include('private/logdata.php');
include('private/handleLoginAttempts.php'); // Protezione contro tentativi di login

// Se l'utente è già loggato, reindirizza
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //CAPTCHA V2 passa chiave privata da cartella protetta
    // $secret_key = "6LeNDcoqAAAAAGxnZBAE44lwFXjfSHbm84LtpvSP";
    // $secret_key = RECAPTCHA_SECRET_KEY;
    $secret_key = RECAPTCHA_SECRET_KEY;
    $response_key = $_POST['g-recaptcha-response'];
    $user_ip = $_SERVER['REMOTE_ADDR'];

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response_key&remoteip=$user_ip";
    $response = file_get_contents($url);
    $response = json_decode($response);

    if ($response->success) {
        // Il CAPTCHA è stato superato, procedi con l'elaborazione del form
        echo "CAPTCHA superato!";


    // Sanificazione dei dati in ingresso XSS
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $ip_address = $_SERVER['REMOTE_ADDR']; // IP dell'utente

    // Controllo tentativi prima di procedere
    if (handleLoginAttempts($conn, $username, $ip_address)) {
        $error = "Troppi tentativi di login. Attendi qualche minuto prima di riprovare.";
    } else {
        // Verifica che i campi non siano vuoti
        if (!empty($username) && !empty($password)) {
            try {
                // Query per trovare l'utente
                $stmt = $conn->prepare("SELECT * FROM users WHERE users_email = :username");
                $stmt->bindValue(':username', $username, PDO::PARAM_STR);
                $stmt->execute();
                
                // Recupera i dati
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['users_password'])) {
                    // Credenziali corrette, avvia sessione
                    session_regenerate_id(true);
                    $_SESSION['username'] = $user['users_email'];

                    // Resetta i tentativi falliti e salva il log
                    resetLoginAttempts($conn, $username, $ip_address);
                    logLogin($conn, $username, $ip_address, true);

                    header("Location: index.php");
                    exit();
                } else {
                    // Login fallito
                    $error = "Username o password errati!";
                    logLogin($conn, $username, $ip_address, false);
                    incrementLoginAttempts($conn, $username, $ip_address);
                }
            } catch (PDOException $e) {
                $error = "Errore del server. Riprova più tardi.";
            }
        } else {
            $error = "Compila tutti i campi.";
        }
    }
}   else {
    // Il CAPTCHA non è stato superato, mostra un errore
    echo "Errore CAPTCHA. Riprova.";
    exit(); // Blocca il processo di login
}
}
?>

<form action="login.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <div class="g-recaptcha" data-sitekey="6LeNDcoqAAAAAKGM67-Ybswj0hWYlJhIscXcCAGh"></div>
    <br/>
    <input type="submit" value="Login">
</form>

<?php include("includes/footer.php"); ?>
