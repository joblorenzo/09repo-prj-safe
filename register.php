<?php
// Includi il file di configurazione della sessione (deve essere il primo)
include('private/session_config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      die("Errore CSRF: richiesta non valida.");
  }
}

$pageTitle = "Registrazione";
include("includes/header.php");
include("private/db.php"); // Connessione al database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanificazione dei dati in ingresso XSS
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');  // username = users_email
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');  // password = users_password
    // Controllo se i campi non sono vuoti
    if (!empty($username) && !empty($password)) {
        // Verifica se lo username esiste già
        $stmt = $conn->prepare("SELECT * FROM users WHERE users_email = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<p style='color:red;'>Errore: Username già in uso. Scegline un altro.</p>";
        } else {
            // Hash della password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            echo "<br> pw ****** == $password <br>";
            echo "pw hashed == $hashedPassword <br>";
            echo "Algoritmo di hash == $2y$ tipo Bcrypt, $10 n iterazioni costo calcolo, resto è generato in automatico<br>";

            // Preparazione della query per inserire i dati
            $stmt = $conn->prepare("INSERT INTO users (users_email, users_password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);

            // Eseguiamo la query
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Registrazione completata! <a href='login.php'>Accedi ora</a></p>";
            } else {
                echo "<p style='color:red;'>Errore durante la registrazione. Riprova.</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>Per favore, riempi tutti i campi.</p>";
    }
}
?>

<form action="register.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <!-- Aggiungi il reCAPTCHA v2 -->
    <div class="g-recaptcha" data-sitekey="6LeNDcoqAAAAAKGM67-Ybswj0hWYlJhIscXcCAGh"></div>
    <input type="submit" value="Registrati">
</form>

<?php
include("includes/footer.php");
?>
