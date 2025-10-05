<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'soignant') {
    header("Location: ../auth/login.php");
    exit();
}

$alerteFile = '../patient/alerte_soignant/alertes.json'; // chemin vers le fichier JSON

// Suppression d'une alerte par son index
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = (int) $_POST['index'];
    if (file_exists($alerteFile)) {
        $alertes = json_decode(file_get_contents($alerteFile), true);
        if (isset($alertes[$index])) {
            array_splice($alertes, $index, 1); // Supprime l'alerte
            file_put_contents($alerteFile, json_encode($alertes, JSON_PRETTY_PRINT));
        }
    }
    header("Location: alertes.php"); // Évite le repost si on rafraîchit
    exit();
}

// Lecture des alertes
$alertes = file_exists($alerteFile) ? json_decode(file_get_contents($alerteFile), true) : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alertes des patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css?v=1.0" rel="stylesheet">
    <link rel="stylesheet" href="alertes.css?v=1.0">
</head>
<body>

<div class="container">
    <h1>Alertes des patients</h1>
    <div id="alertes-container">
        <div class="text-center text-muted">Chargement des alertes...</div>
    </div>
</div>
<script>
function chargerAlertes() {
    fetch('get_alertes.php')
        .then(res => res.text())
        .then(html => {
            document.getElementById('alertes-container').innerHTML = html;
            activerSuppression();
        });
}

// Fonction pour rendre les boutons "Supprimer" actifs
function activerSuppression() {
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const index = this.dataset.index;

            fetch('supprimer_alerte.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'index=' + encodeURIComponent(index)
            })
            .then(res => res.text())
            .then(() => {
                chargerAlertes();
            });
        });
    });
}

// Premier chargement et boucle
chargerAlertes();
setInterval(chargerAlertes, 10000);
</script>
</body>
</html>
