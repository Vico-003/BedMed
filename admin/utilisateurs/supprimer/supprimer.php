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

$type = $_GET['type'] ?? '';
$id = (int)($_GET['id'] ?? 0);
$message = '';

// Liste des tables autorisées
$allowedTypes = ['patient' => 'Patient', 'soignant' => 'Soignant', 'admin' => 'Admin'];
$table = $allowedTypes[$type] ?? null;

// Fonction pour récupérer l'identifiant par ID
function getIdentifiantById($id, $conn, $table) {
    $result = $conn->query("SELECT identifiant FROM $table WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        return $row['identifiant'];
    }
    return null;
}

if ($id > 0 && $table) {
    $check = $conn->query("SELECT id FROM $table WHERE id = $id");
    if ($check && $check->num_rows > 0) {
        $targetIdentifiant = getIdentifiantById($id, $conn, $table);

        // Bloque la suppression de son propre compte admin
        if ($type === 'admin' && $_SESSION['username'] === $targetIdentifiant) {
            $message = "❌ Vous ne pouvez pas supprimer votre propre compte administrateur.";
        } else {
            if ($conn->query("DELETE FROM $table WHERE id = $id")) {
                $message = "✅ $type supprimé avec succès.";
            } else {
                $message = "❌ Erreur lors de la suppression : " . $conn->error;
            }
        }
    } else {
        $message = "❌ Utilisateur introuvable.";
    }
} else {
    $message = "❌ Paramètres invalides.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression</title>
    <link rel="stylesheet" href="supprimer.css">
    <meta http-equiv="refresh" content="3;url=../utilisateurs.php">
</head>
<body>
    <div class="message">
        <?= $message ?><br>
        Redirection imminente...
    </div>
</body>
</html>
