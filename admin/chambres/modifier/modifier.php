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
if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

$id = (int)($_GET['id'] ?? 0);
$message = '';
$success = false;

if ($id <= 0) {
    die("❌ ID de chambre invalide.");
}

// Récupération de la chambre
$result = $conn->query("SELECT * FROM Chambre WHERE id_chambre = $id");
if (!$result || $result->num_rows === 0) {
    die("❌ Chambre introuvable.");
}
$chambre = $result->fetch_assoc();

// Mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = (int)$_POST['numero'];
    $temperature = (float)$_POST['temperature'];
    $humidite = (float)$_POST['humidite'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $sql = "UPDATE Chambre 
            SET numerochambre = $numero, temperature = $temperature, humidite = $humidite, disponible = $disponible 
            WHERE id_chambre = $id";

    if ($conn->query($sql)) {
        $message = "✅ Chambre modifiée avec succès.";
        $success = true;
        $chambre = $conn->query("SELECT * FROM Chambre WHERE id_chambre = $id")->fetch_assoc(); // refresh data
    } else {
        $message = "❌ Erreur : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une chambre</title>
    <link rel="stylesheet" href="modifier.css">
    <?php if ($success): ?>
        <meta http-equiv="refresh" content="3;url=../chambres.php">
    <?php endif; ?>
</head>
<body>
    <header>
        <h1>Modifier une chambre</h1>
    </header>

    <nav>
        <a href="../../accueil/dashboard.php">🏠 Accueil</a>
        <a href="../../utilisateurs/utilisateurs.php">👥 Utilisateurs</a>
        <a href="../chambres.php">🛏 Chambres</a>
        <a href="../../interventions/interventions.php">🩺 Interventions</a>
        <a href="../../../auth/logout.php">🚪 Déconnexion</a>
    </nav>

    <?php if (!empty($message)): ?>
        <div class="message" style="text-align:center; font-weight:bold; margin: 20px; color: <?= $success ? 'green' : 'red' ?>">
            <?= $message ?><br>
            <?php if ($success): ?>
                Redirection imminente...
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Numéro de chambre</label>
        <input type="number" name="numero" value="<?= $chambre['numerochambre'] ?>" required>

        <label>Température (°C)</label>
        <input type="number" step="0.1" name="temperature" value="<?= $chambre['temperature'] ?>" required>

        <label>Humidité (%)</label>
        <input type="number" step="0.1" name="humidite" value="<?= $chambre['humidite'] ?>" required>

        <label>
            <input type="checkbox" name="disponible" <?= $chambre['disponible'] ? 'checked' : '' ?>>
            Disponible
        </label>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
