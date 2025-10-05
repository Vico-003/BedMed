<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$conn = new mysqli(
    'bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com',
    'up0gqrwfyet1kt3b',
    'LjMVU9QGJFxqDutI1C7l',
    'bdjiqcyfkuixyubw0fdd',
    3306
);
if ($conn->connect_error) die("Erreur DB : " . $conn->connect_error);

$result = $conn->query("SELECT * FROM Chambre ORDER BY numerochambre ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des chambres</title>
    <link rel="stylesheet" href="chambres.css">
</head>
<body>
    <header>
        <h1>Gestion des chambres</h1>
    </header>

    <nav>
        <a href="../accueil/dashboard.php">🏠 Accueil</a>
        <a href="../utilisateurs/utilisateurs.php">👥 Utilisateurs</a>
        <a href="../interventions/interventions.php">🩺 Interventions</a>
        <a href="../../auth/logout.php">🚪 Déconnexion</a>
    </nav>

    <div class="top-actions">
        <a class="btn" href="ajouter/ajouter.php">➕ Ajouter une chambre</a>
    </div>

<table>
    <tr>
        <th>ID</th>
        <th>Numéro</th>
        <th>Humidité</th>
        <th>Température</th>
        <th>Disponibilité</th>
        <th>Actions</th>
    </tr>
    <?php while ($c = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $c['id_chambre'] ?></td>
            <td><?= htmlspecialchars($c['numerochambre']) ?></td>
            <td><?= $c['humidite'] ?> %</td>
            <td><?= $c['temperature'] ?> °C</td>
            <td><?= $c['disponible'] ? '✅ Disponible' : '❌ Occupée' ?></td>
            <td>
                <a class="btn" href="modifier/modifier.php?id=<?= $c['id_chambre'] ?>">Modifier</a>
                <a class="btn btn-danger" href="supprimer/supprimer.php?id=<?= $c['id_chambre'] ?>" onclick="return confirm('Supprimer cette chambre ?')">Supprimer</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

    <footer>
        <p>&copy; <?= date('Y') ?> - Gestion des chambres</p>
    </footer>
</body>
</html>