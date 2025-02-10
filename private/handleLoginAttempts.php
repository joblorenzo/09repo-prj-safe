<?php
function handleLoginAttempts($conn, $username, $ip_address) {
    $now = new DateTime();
    $thresholdTime = $now->sub(new DateInterval('PT30M'))->format('Y-m-d H:i:s'); // Considera i tentativi negli ultimi 30 minuti

    // Controlla gli ultimi tentativi di login per username o IP
    $stmt = $conn->prepare("SELECT id, attempt_count, block_until, last_attempt_time 
                            FROM login_attempts 
                            WHERE ip_address = :ip_address OR username = :username
                            ORDER BY last_attempt_time DESC LIMIT 1");
    $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        $attempt_count = $record['attempt_count'];
        $block_until = $record['block_until'] ? new DateTime($record['block_until']) : null;

        // Se l'utente è bloccato, impedisce l'accesso
        if ($block_until && $block_until > new DateTime()) {
            return true; // Login bloccato
        }
    }

    return false; // Login consentito
}

function incrementLoginAttempts($conn, $username, $ip_address) {
    $now = new DateTime();
    $thresholdTime = $now->sub(new DateInterval('PT30M'))->format('Y-m-d H:i:s');

    // Controlla se esiste un record recente
    $stmt = $conn->prepare("SELECT id, attempt_count, last_attempt_time 
                            FROM login_attempts 
                            WHERE ip_address = :ip_address OR username = :username
                            ORDER BY last_attempt_time DESC LIMIT 1");
    $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        $attempt_count = $record['attempt_count'];
        $last_attempt_time = new DateTime($record['last_attempt_time']);

        // Incrementa il conteggio se il tentativo è entro i 30 minuti
        if ($last_attempt_time > new DateTime($thresholdTime)) {
            $attempt_count++;
        } else {
            $attempt_count = 1; // Resetta se il tentativo è troppo vecchio
        }

        // Determina il blocco
        $block_time = null;
        if ($attempt_count > 10) {
            $block_time = new DateTime();
            $block_time->add(new DateInterval('PT30M')); // Blocca per 30 minuti
        } elseif ($attempt_count > 5) {
            $block_time = new DateTime();
            $block_time->add(new DateInterval('PT5M')); // Blocca per 5 minuti
        }

        // Aggiorna il record con il nuovo conteggio e il tempo di blocco
        $stmt = $conn->prepare("UPDATE login_attempts 
                                SET attempt_count = :attempt_count, block_until = :block_until, last_attempt_time = :last_attempt_time 
                                WHERE id = :id");
        $stmt->bindValue(':attempt_count', $attempt_count, PDO::PARAM_INT);
        $stmt->bindValue(':block_until', $block_time ? $block_time->format('Y-m-d H:i:s') : null, PDO::PARAM_STR);
        $stmt->bindValue(':last_attempt_time', (new DateTime())->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':id', $record['id'], PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Nessun record trovato, crea un nuovo tentativo
        $stmt = $conn->prepare("INSERT INTO login_attempts (username, ip_address, last_attempt_time, attempt_count) 
                                VALUES (:username, :ip_address, :last_attempt_time, 1)");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
        $stmt->bindValue(':last_attempt_time', (new DateTime())->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->execute();
    }
}

// Funzione per resettare i tentativi di login dopo un accesso riuscito
function resetLoginAttempts($conn, $username, $ip_address) {
  // Reset dei tentativi di login dopo il successo
  $stmt = $conn->prepare("UPDATE login_attempts 
                          SET attempt_count = 0, block_until = NULL 
                          WHERE username = :username OR ip_address = :ip_address");
  $stmt->bindValue(':username', $username, PDO::PARAM_STR);
  $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
  $stmt->execute();
}
?>
