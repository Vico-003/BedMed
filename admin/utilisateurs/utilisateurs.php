<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

$conn = new mysqli(
    'bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com',
    'up0gqrwfyet1kt3b',
    'LjMVU9QGJFxqDutI1C7l',
    'bdjiqcyfkuixyubw0fdd',
    3306
);
if ($conn->connect_error) die("Erreur connexion DB : " . $conn->connect_error);

// Récupération des patients avec numéro de chambre
$patients = $conn->query("
    SELECT p.*, c.numerochambre 
    FROM Patient p 
    LEFT JOIN Chambre c ON p.id_chambre = c.id_chambre
");

$soignants = $conn->query("SELECT * FROM Soignant");
$admins = $conn->query("SELECT * FROM Admin");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="utilisateurs.css">
</head>
<body>
    <header>
        <h1>Gestion des utilisateurs</h1>
    </header>

    <nav>
        <a href="../accueil/dashboard.php">🏠 Accueil</a>
        <a href="../chambres/chambres.php">🛏 Chambres</a>
        <a href="../interventions/interventions.php">🩺 Interventions</a>
        <a href="../../auth/logout.php">🚪 Déconnexion</a>
    </nav>

    <div class="top-actions">
        <a class="btn" href="ajouter/ajouter.php">➕ Ajouter un utilisateur</a>
    </div>

    <h2>👤 Patients</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Âge</th>
            <th>Chambre</th>
            <th>Actions</th>
        </tr>
        <?php while ($p = $patients->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['nom']) ?></td>
                <td><?= htmlspecialchars($p['prenom']) ?></td>
                <td><?= $p['age'] ?></td>
                <td><?= $p['numerochambre'] ? 'N° ' . $p['numerochambre'] : '—' ?></td>
                <td>
                    <a class="btn" href="modifier/modifier.php?type=patient&id=<?= $p['id'] ?>">Modifier</a>
                    <a class="btn btn-danger" href="supprimer/supprimer.php?type=patient&id=<?= $p['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>🩺 Soignants</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Badge</th>
            <th>Emploi du temps</th>
            <th>Actions</th>
        </tr>
        <?php while ($s = $soignants->fetch_assoc()): ?>
            <?php
            // Récupération de l'emploi du temps pour chaque soignant
            $id_soignant = $s['id'];
            $edt_result = $conn->query("SELECT jour, heure_debut, heure_fin FROM Emploi_Temps WHERE id_soignant = $id_soignant ORDER BY FIELD(jour, 'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche')");
            $emploi_du_temps = "";
            while ($edt = $edt_result->fetch_assoc()) {
                $emploi_du_temps .= $edt['jour'] . " : " . substr($edt['heure_debut'], 0, 5) . " - " . substr($edt['heure_fin'], 0, 5) . "<br>";
            }
            ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['nom']) ?></td>
                <td><?= htmlspecialchars($s['prenom']) ?></td>
                <td><?= htmlspecialchars($s['uid_badge'] ?? '') ?></td>
                <td><?= $emploi_du_temps ?: '—' ?></td>
                <td>
                    <a class="btn" href="modifier/modifier.php?type=soignant&id=<?= $s['id'] ?>">Modifier</a>
                    <a class="btn btn-danger" href="supprimer/supprimer.php?type=soignant&id=<?= $s['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>👮‍♂️ Admins</h2>
    <table>
        <tr>
            <th>ID</th><th>Nom</th><th>Prénom</th><th>Identifiant</th><th>Actions</th>
        </tr>
        <?php while ($a = $admins->fetch_assoc()): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['nom']) ?></td>
                <td><?= htmlspecialchars($a['prenom']) ?></td>
                <td><?= htmlspecialchars($a['identifiant']) ?></td>
                <td>
                    <a class="btn" href="modifier/modifier.php?type=admin&id=<?= $a['id'] ?>">Modifier</a>
                    <a class="btn btn-danger" href="supprimer/supprimer.php?type=admin&id=<?= $a['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <footer>
        <p>&copy; <?= date('Y') ?> - Administration</p>
    </footer>
</body>
</html>
