<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

$prenom = $_SESSION['prenom'] ?? $_SESSION['username'];

$conn = new mysqli(
    'bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com',
    'up0gqrwfyet1kt3b',
    'LjMVU9QGJFxqDutI1C7l',
    'bdjiqcyfkuixyubw0fdd',
    3306
);
if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

// Statistiques générales
$nb_patients = $conn->query("SELECT COUNT(*) AS total FROM Patient")->fetch_assoc()['total'];
$nb_soignants = $conn->query("SELECT COUNT(*) AS total FROM Soignant")->fetch_assoc()['total'];
$chambres = $conn->query("SELECT COUNT(*) AS total, SUM(disponible = 1) AS dispo FROM Chambre")->fetch_assoc();
$temp_moyenne = $conn->query("SELECT AVG(temperature) AS moyenne FROM Chambre WHERE temperature IS NOT NULL")->fetch_assoc()['moyenne'];

// Récupération des chambres avec température
$chambres_temp = [];
$result = $conn->query("SELECT numerochambre FROM Chambre WHERE temperature IS NOT NULL");
while ($row = $result->fetch_assoc()) {
    $chambres_temp[] = $row['numerochambre'];
}
$liste_chambres = implode(', ', $chambres_temp);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Admin</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Bienvenue, <?= htmlspecialchars($prenom) ?> 👋</h1>
    </header>

    <nav>
        <a href="../utilisateurs/utilisateurs.php">👥 Utilisateurs</a>
        <a href="../chambres/chambres.php">🛏 Chambres</a>
        <a href="../interventions/interventions.php">🩺 Interventions</a>
        <a href="../../auth/logout.php">🚪 Déconnexion</a>
    </nav>
    
    <div class="cards">
        <div class="card">
            <h2>👤 Patients</h2>
            <p><?= $nb_patients ?></p>
        </div>
        <div class="card">
            <h2>🩺 Soignants</h2>
            <p><?= $nb_soignants ?></p>
        </div>
        <div class="card">
            <h2>🛏 Chambres</h2>
            <p><?= $chambres['total'] ?> total<br><?= $chambres['dispo'] ?> dispo</p>
        </div>
        <div class="card">
            <h2>🌡 Température moyenne</h2>
            <p><?= round($temp_moyenne, 1) ?> °C</p>
            <small>Chambres : <?= $liste_chambres ?: 'Aucune' ?></small>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> - Tableau de bord administrateur</p>
    </footer>
</body>
</html>