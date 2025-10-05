<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}

$prenom = $_SESSION['prenom']; // Récupère le prénom depuis la session
?>
<?php
$arduino_ip = "192.168.1.117"; // Remplace par l'IP réelle de ton Arduino

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande = $_POST['commande'];

    // Envoie une requête HTTP vers l'Arduino
    $url = "http://$arduino_ip/$commande";

    // Envoie simple avec file_get_contents
    @file_get_contents($url); // @ pour éviter les warnings si Arduino ne répond pas
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrôler le lit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="control.css?v=1.0">
</head>
<body>

<header>
    <div class="username">Contrôler le lit</div>
    <a href="../../auth/logout.php" class="logout-icon">
        <i class="bi bi-box-arrow-right"></i>
    </a>
</header>

<div class="sidebar">
    <img src="../../images/logo bedmed.png" alt="Logo BEDMED" class="logo">
    <a href="../accueil/accueil.php" class="icon-btn maison">
        <img src="../../images/maison.png" alt="Accueil">
    </a>
    <a href="../alerte_soignant/alerte.php" class="icon-btn alerte">
        <img src="../../images/alerte.png" id="icone-alert" alt="Alerte">
    </a>
    <a href="../control_lit/control.php" class="icon-btn lit">
        <img src="../../images/lit.png" alt="lit">
    </a>
    <a href="../abonnement/abonnement.php" class="icon-btn tele">
        <img src="../../images/tv.png" alt="Abonnement">
    </a>
    <img src="../../images/logo chu.png" alt="CHU Lille" class="chu-logo">
</div>

<main class="lit-wrapper">
    <div class="lit-card">
        <!-- Hauteur du lit -->
        <div class="control-section">
            <button class="arrow-btn" data-commande="up_hauteur">
                <img src="../../images/fleche.png" alt="Monter hauteur" class="arrow-icon">
            </button>

            <img src="../../images/bed-flat.png" alt="Lit bas" class="bed-image">

            <button class="arrow-btn" data-commande="down_hauteur">
                <img src="../../images/fleche.png" alt="Descendre hauteur" class="arrow-icon rotate-down">
            </button>
        </div>

        <!-- Dossier du lit -->
        <div class="control-section dossier">
            <button class="arrow-btn offset-left" data-commande="up_dossier">
                <img src="../../images/fleche.png" alt="Incliner dossier" class="arrow-icon">
            </button>

            <img src="../../images/bed-incline.png" alt="Lit incliné" class="bed-image">

            <button class="arrow-btn offset-left" data-commande="down_dossier">
                <img src="../../images/fleche.png" alt="Abaisser dossier" class="arrow-icon rotate-down">
            </button>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('.arrow-btn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const commande = this.dataset.commande;

        fetch('control.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'commande=' + encodeURIComponent(commande)
        })
        .then(response => response.text())
        .then(data => {
            console.log('Réponse Arduino :', data);
        })
        .catch(error => {
            console.error('Erreur AJAX :', error);
        });
    });
});
</script>
</body>
</html>