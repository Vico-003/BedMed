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

// Requête SQL correcte avec les vrais noms de colonnes
$sql = "SELECT i.*, 
               s.nom AS nom_soignant, s.prenom AS prenom_soignant,
               p.nom AS nom_patient, p.prenom AS prenom_patient
        FROM Intervention i
        LEFT JOIN Soignant s ON i.soignant_id = s.id
        LEFT JOIN Patient p ON i.patient_id = p.id
        ORDER BY i.date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des interventions</title>
    <link rel="stylesheet" href="interventions.css">
</head>
<body>
    <header>
        <h1>Historique des interventions</h1>
    </header>

    <nav>
        <a href="../dashboard.php">🏠 Accueil</a>
        <a href="../utilisateurs/utilisateurs.php">👥 Utilisateurs</a>
        <a href="../chambres/chambres.php">🛏 Chambres</a>
        <a href="../auth/logout.php">🚪 Déconnexion</a>
    </nav>

    <table>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Soignant</th>
            <th>Patient</th>
            <th>Contenu</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= date("d/m/Y", strtotime($row['date'])) ?></td>
                <td><?= $row['heure_inter'] ?></td>
                <td><?= htmlspecialchars($row['prenom_soignant'] . ' ' . $row['nom_soignant']) ?></td>
                <td><?= htmlspecialchars($row['prenom_patient'] . ' ' . $row['nom_patient']) ?></td>
                <td><?= htmlspecialchars($row['contenu']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <footer>
        <p>&copy; <?= date('Y') ?> - Suivi des interventions</p>
    </footer>
</body>
</html>
