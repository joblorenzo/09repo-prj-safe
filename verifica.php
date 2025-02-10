<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secret_key = "6LeNDcoqAAAAAGxnZBAE44lwFXjfSHbm84LtpvSP";
    $response_key = $_POST['g-recaptcha-response'];
    $user_ip = $_SERVER['REMOTE_ADDR'];

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response_key&remoteip=$user_ip";
    $response = file_get_contents($url);
    $response = json_decode($response);

    if ($response->success) {
        // Il CAPTCHA è stato superato, procedi con l'elaborazione del form
        echo "CAPTCHA superato!";
    } else {
        // Il CAPTCHA non è stato superato, mostra un errore
        echo "Errore CAPTCHA. Riprova.";
    }
}
?>