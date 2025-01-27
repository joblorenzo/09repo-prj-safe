<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../assets/style.css">

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Simple client-side redirect as a demonstration
            const redirectBtn = document.getElementById("redirectBtn");

            redirectBtn.addEventListener("click", () => {
                alert("Reindirizzamento in corso...");
                window.location.href = "login.php"; // Example external redirect
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Benvenuto nel nostro sito</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Registrazione</a>
        </nav>
        <hr>
    </header>
