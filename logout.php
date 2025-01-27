<?php
session_start();
session_destroy(); // Distrugge la sessione
header("Location: index.php"); // Reindirizza alla homepage
exit();
?>
