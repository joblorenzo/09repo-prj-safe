<?php
session_start(); // Avvia la sessione
include('private/db.php'); // Include il file di connessione al database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica base delle credenziali
    if ($username == "admin" && $password == "password123") {
        $_SESSION['username'] = $username;
        header("Location: index.php"); // Reindirizza alla homepage
        exit();
    } else {
        $error = "Credenziali non valide!";
    }
}

$pageTitle = "Login";

include("includes/header.php");

if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}

?>

<form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

<?php
include("includes/footer.php");
?>


<!-- a1@a.com  pw1 -->
