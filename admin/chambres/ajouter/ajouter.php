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

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = (int)$_POST['numero'];
    $temperature = (float)$_POST['temperature'];
    $humidite = (float)$_POST['humidite'];

    $sql = "INSERT INTO Chambre (numerochambre, temperature, humidite, disponible)
            VALUES ($numero, $temperature, $humidite, 1)";

    if ($conn->query($sql)) {
        $message = "✅ Chambre ajoutée avec succès.";
        $success = true;
    } else {
        $message = "❌ Erreur : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une chambre</title>
    <link rel="stylesheet" href="ajouter.css">
    <?php if ($success): ?>
        <meta http-equiv="refresh" content="3;url=../chambres.php">
    <?php endif; ?>
</head>
<body>
    <header>
        <h1>Ajouter une chambre</h1>
    </header>

    <nav>
        <a href="../../accueil/dashboard.php">🏠 Accueil</a>
        <a href="../../utilisateurs/utilisateurs.php">👥 Utilisateurs</a>
        <a href="../chambres.php">🛏 Chambres</a>
        <a href="../../interventions/interventions.php">🩺 Interventions</a>
        <a href="../../auth/logout.php">🚪 Déconnexion</a>
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
        <input type="number" name="numero" required>

        <label>Température (°C)</label>
        <input type="number" step="0.1" name="temperature" required>

        <label>Humidité (%)</label>
        <input type="number" step="0.1" name="humidite" required>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>