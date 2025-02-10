<?php
// Includi il file di configurazione della sessione (deve essere il primo)
include('private/session_config.php');

session_unset(); // Rimuove tutte le variabili di sessione, elimina tracce dati
session_destroy(); // Distrugge la sessione
// Reindirizza l'utente alla pagina di login o alla homepage
header("Location: login.php"); // O puoi usare index.php, dipende dalla tua logica
exit();
?>
