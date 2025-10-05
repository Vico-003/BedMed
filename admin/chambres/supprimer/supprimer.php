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
if ($conn->connect_error) die("Erreur DB : " . $conn->connect_error);

$id = (int)($_GET['id'] ?? 0);
$message = '';

if ($id > 0) {
    // Vérifie si la chambre existe
    $check = $conn->query("SELECT id_chambre FROM Chambre WHERE id_chambre = $id");
    if ($check && $check->num_rows > 0) {
        if ($conn->query("DELETE FROM Chambre WHERE id_chambre = $id")) {
            $message = "✅ Chambre supprimée avec succès.";
        } else {
            $message = "❌ Erreur lors de la suppression : " . $conn->error;
        }
    } else {
        $message = "❌ Chambre introuvable.";
    }
} else {
    $message = "❌ ID invalide.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression de chambre</title>
    <link rel="stylesheet" href="supprimer.css">
    <meta http-equiv="refresh" content="3;url=../chambres.php">
</head>
<body>
    <div class="message">
        <?= $message ?><br>
        Redirection imminente...
    </div>
</body>
</html>
