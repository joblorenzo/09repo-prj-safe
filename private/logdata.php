<?php
function logLogin($conn, $username, $ip_address, $success) {
        // Log del tentativo di login (successo o fallito)
        $stmt = $conn->prepare("INSERT INTO login_logs (username, ip_address, success) VALUES (:username, :ip_address, :success)");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);  // Stringa
        $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR); // Stringa
        $stmt->bindValue(':success', $success, PDO::PARAM_BOOL);  // Booleano
        $stmt->execute();
        echo "Log registrato.<br>";   
    //inviamo i dati di log in dato di success false or true, se l'utente ha loggato o l utente X ha provato a loggarsi, salva IP
}
?>