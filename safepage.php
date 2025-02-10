<?php
// Includi il file di configurazione della sessione (deve essere il primo)
include('private/session_config.php');

// Controlla se l'utente è autenticato
if (!isset($_SESSION['username'])) {
    // Se l'utente non è autenticato, reindirizzalo al login
    header("Location: login.php");
    exit();
}

$pageTitle = "Pagina Protetta";
include("includes/header.php");
?>

<h1>Benvenuto nella pagina protetta!</h1>
<p>Questa pagina è accessibile solo agli utenti autenticati.</p>

<?php
include("includes/footer.php");
?>

<!-- 
______________________________________-
_______________________________________-
_______________________________________-
SW penetration testing distro Parrot(hide) || Kali(common) || BlackArch(PRO)
win+R cmd
wsl -l -v
wsl --install -d kali-linux
#set user e pw
1 update package list == sudo apt update && sudo apt upgrade -y
2 install penetration testing package common == sudo apt install kali-tools-top10
3 network scanning tools (scansiona porte) == sudo apt install nmap
    #abilita non-superUser a visionare pacchetti? su Raspberry pi non importa
4 sudo apt update
5 sudo apt upgrade
    #già installati probabilmente nei pacchetti precedenti
    #come nmap (scansione delle porte), Metasploit (framework di exploit), Burp Suite (intercettazione del traffico web), e Wireshark (cattura pacchetti di rete).
6 sudo apt install nmap metasploit-framework wireshark
7 sudo apt install setoolkit == pishingtool crea pagina falsa per user e ruba credenziali???
    sudo setoolkit
8 sudo apt install tor == tor navigazione anonima
    sudo systemctl start tor == avvia tor (se usi servise al posto systemctl è + vecchio e meno performante)
    sudo systemctl status tor == se running propriamente check
#TOR NON RENDE ANONIMI sui nodi di uscita di rete
    00 CONNESSIONE LIBERA + TOR + VPN migliora l'anonimato
    (alternative I2P freenet(decentralizzata per scambio file anonimo e comunicazione))
    PROTONVPN anche gratuita non è male
    EXPRESSVPN veloce e costosa

    su wls2 o wsl puoi avere problemi
    sudo tor
    curl https://check.torproject.org == verifica connessione a tor
    setup browser channel
    firefox - impostazioni - impostazioni di rete - configurazione manuale proxy - hostsoks 127.0.0.1 port9050 type socksV5
    spunta proyDNS socksv5 == evita perdita pacchetti DNS che rileverebbero la tua posizione
    chrome === impostazioni - avanzate - sistema - impostazioni proxy = impostazioni rete 127.0.0.1 proxy sockv5 port9050
    
_______
SCAN WEB nmap ports
    identifica gateway in subnet
    Windows cmd
    1 identifica router == ipconfig
    #default gateway == ip
    Linux
    1 ip route | grep default


    scan ports host == sudo nmap -sS 192.168.1.1
    siccome sei una subnet devi identificare il tuo gateway ip
    sudo nmap -sS -p 1-65535 192.168.18.1


    scan ports dispositivi rete == sudo nmap -sn 192.168.1.0/24

-->
