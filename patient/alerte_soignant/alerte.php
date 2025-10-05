<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../../login/login.php");
    exit();
}

$prenom = $_SESSION['prenom']; // Récupère le prénom depuis la session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alerter un soignant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="alerte.css?v=1.0">
</head>
<body>

<header>
    <div class="username">Alerter un soignant</div>
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

<?php
// Traitement alerte
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type_alerte'])) {
    $alertes = file_exists('alertes.json') ? json_decode(file_get_contents('alertes.json'), true) : [];

    $nouvelleAlerte = [
        'type' => $_POST['type_alerte'],                 // Ne pas encoder ici
        'message' => $_POST['message'] ?? '',            // Ne pas encoder ici
        'date' => date('Y-m-d H:i:s'),
        'from' => $prenom
    ];

    $alertes[] = $nouvelleAlerte;

    // Ajout du flag JSON_UNESCAPED_UNICODE pour conserver les accents
    file_put_contents('alertes.json', json_encode($alertes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $alerteEnvoyee = true;
}
?>

<main class="main-content">
    <div class="alerte-container">
        <h2>Envoyer une alerte</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Message (facultatif)</label>
                <textarea name="message" class="form-control" placeholder="Entrez un message..." rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-around mt-4">
                <button type="submit" name="type_alerte" value="Alerte chambre" class="btn btn-danger btn-lg">
                    Alerte chambre
                </button>
                <button type="submit" name="type_alerte" value="Alerte assistance" class="btn btn-warning btn-lg">
                    Alerte assistance
                </button>
            </div>
        </form>
        <?php if (!empty($alerteEnvoyee)): ?>
            <div class="alert alert-success text-center mt-4">✅ Alerte envoyée avec succès !</div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>