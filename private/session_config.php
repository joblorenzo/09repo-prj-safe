<?php
// Configura i parametri del cookie di sessione
session_set_cookie_params([
    'lifetime' => 0,         // La sessione termina alla chiusura del browser
    'secure' => true,        // Usa solo HTTPS if true
    'httponly' => true,      // Impedisce l'accesso via JavaScript if true
    'samesite' => 'Strict',  // Protegge contro attacchi CSRF
]);

session_start(); // Avvia la sessione
// Timeout in secondi (ad esempio 30 minuti)
$timeout_duration = 30 * 60; // 30 minuti
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();     // Rimuove tutte le variabili di sessione
    session_destroy();   // Distrugge la sessione
    header("Location: login.php"); // Reindirizza alla pagina di login
    exit();
}
$_SESSION['last_activity'] = time(); // Imposta il tempo dell'ultima attività

// Genera il token CSRF se non esiste
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>