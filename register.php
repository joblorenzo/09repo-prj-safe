<?php
session_start();
$pageTitle = "Registrazione";

include("includes/header.php");
include("private/db.php"); // Connessione al database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Controllo se i campi non sono vuoti
    if (!empty($username) && !empty($password)) {
        try {
            // Preparazione della query per inserire i dati
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            
            // Hash della password per sicurezza
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            echo "<br> pw ****** == $password <br>";
            echo "pw hashed == $hashedPassword <br>";
            echo "Algoritmo di hash == $2y$ tipo Bcrypt, $10 n iterazioni costo calcolo, resto è generato in automatico <br>";
            
            // Bind dei parametri
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            
            // Esecuzione della query
            $stmt->execute();
            
            echo "<p>Registrazione completata! <a href='login.php'>Accedi ora</a></p>";
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Errore nella registrazione: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Per favore, riempi tutti i campi.</p>";
    }
}

?>

<form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="pa

