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

// Tables autorisées
$allowedTypes = ['patient' => 'Patient', 'soignant' => 'Soignant', 'admin' => 'Admin'];
$table = $allowedTypes[$type] ?? null;

if ($id > 0 && $table) {
    $result = $conn->query("SELECT * FROM $table WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("❌ Utilisateur introuvable.");
    }
} else {
    die("❌ Paramètres invalides.");
}

$chambres = $conn->query("SELECT id_chambre, numerochambre FROM Chambre ORDER BY numerochambre ASC");

// Liste des jours pour emploi du temps
$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);

    if ($type === 'patient') {
        $age = (int)$_POST['age'];
        $historique = $conn->real_escape_string($_POST['historique']);
        $motif = $conn->real_escape_string($_POST['motif']);
        $mobilite = $conn->real_escape_string($_POST['mobilite']);
        $date_entree = $_POST['date_entree'];
        $id_chambre = (int)$_POST['id_chambre'];

        $sql = "UPDATE $table SET nom='$nom', prenom='$prenom', age=$age, historique='$historique', motif='$motif', mobilite='$mobilite', date_entree='$date_entree', id_chambre=$id_chambre WHERE id=$id";

    } elseif ($type === 'soignant') {
        $badge = $conn->real_escape_string($_POST['badge']);
        $sql = "UPDATE $table SET nom='$nom', prenom='$prenom', uid_badge='$badge' WHERE id=$id";

        // Mettre à jour l'emploi du temps
        $conn->query("DELETE FROM Emploi_Temps WHERE id_soignant = $id");

        foreach ($jours as $jour) {
            $debut = $_POST["heure_debut_$jour"] ?? '';
            $fin = $_POST["heure_fin_$jour"] ?? '';
            if ($debut && $fin) {
                $debut = $conn->real_escape_string($debut);
                $fin = $conn->real_escape_string($fin);
                $conn->query("INSERT INTO Emploi_Temps (id_soignant, jour, heure_debut, heure_fin) 
                              VALUES ($id, '$jour', '$debut', '$fin')");
            }
        }
    } elseif ($type === 'admin') {
        $sql = "UPDATE $table SET nom='$nom', prenom='$prenom' WHERE id=$id";
    }

    if (isset($sql) && $conn->query($sql)) {
        $message = "✅ Modification réussie.";
        $user = $conn->query("SELECT * FROM $table WHERE id = $id")->fetch_assoc();
    } else {
        $message = "❌ Erreur lors de la mise à jour : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier <?= ucfirst($type) ?></title>
    <link rel="stylesheet" href="modifier.css">
    <?php if ($message === "✅ Modification réussie.") : ?>
        <meta http-equiv="refresh" content="3;url=../utilisateurs.php">
    <?php endif; ?>
</head>
<body>
    <header>
        <h1>Modifier <?= ucfirst($type) ?></h1>
    </header>

    <nav>
        <a href="../../accueil/dashboard.php">🏠 Dashboard</a>
        <a href="../utilisateurs.php">👥 Utilisateurs</a>
        <a href="../../chambres/chambres.php">🛏 Chambres</a>
        <a href="../../interventions/interventions.php">🩺 Interventions</a>
        <a href="../../../auth/logout.php">🚪 Déconnexion</a>
    </nav>

    <?php if (!empty($message)): ?>
        <div class="message" style="text-align:center; font-weight:bold; margin: 20px; color: <?= strpos($message, '✅') === 0 ? 'green' : 'red' ?>">
            <?= $message ?><br>
            <?php if (strpos($message, '✅') === 0): ?>
                Redirection imminente...
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>

        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

        <?php if ($type === 'patient'): ?>
            <label>Âge</label>
            <input type="number" name="age" value="<?= $user['age'] ?>" min="0">

            <label>Historique</label>
            <textarea name="historique"><?= htmlspecialchars($user['historique']) ?></textarea>

            <label>Motif</label>
            <input type="text" name="motif" value="<?= htmlspecialchars($user['motif']) ?>">

            <label>Mobilité</label>
            <input type="text" name="mobilite" value="<?= htmlspecialchars($user['mobilite']) ?>">

            <label>Date d’entrée</label>
            <input type="date" name="date_entree" value="<?= $user['date_entree'] ?>">

            <label>Chambre assignée</label>
            <select name="id_chambre" required>
                <option value="">-- Sélectionner --</option>
                <?php while ($chambre = $chambres->fetch_assoc()): ?>
                    <option value="<?= $chambre['id_chambre'] ?>" <?= ($user['id_chambre'] == $chambre['id_chambre']) ? 'selected' : '' ?>>
                        Chambre N°<?= $chambre['numerochambre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

        <?php elseif ($type === 'soignant'): ?>
            <label>Badge (UID)</label>
            <input type="text" name="badge" value="<?= htmlspecialchars($user['uid_badge'] ?? '') ?>">

            <fieldset>
                <legend>Emploi du temps</legend>
                <?php
                $edt_actuel = [];
                $edt_result = $conn->query("SELECT jour, heure_debut, heure_fin FROM Emploi_Temps WHERE id_soignant = $id");
                while ($e = $edt_result->fetch_assoc()) {
                    $edt_actuel[$e['jour']] = [$e['heure_debut'], $e['heure_fin']];
                }
                foreach ($jours as $jour):
                    $heure_debut = $edt_actuel[$jour][0] ?? '';
                    $heure_fin = $edt_actuel[$jour][1] ?? '';
                ?>
                    <div style="margin-bottom: 10px;">
                        <strong><?= $jour ?> :</strong><br>
                        De <input type="time" name="heure_debut_<?= $jour ?>" value="<?= $heure_debut ?>"> 
                        à <input type="time" name="heure_fin_<?= $jour ?>" value="<?= $heure_fin ?>">
                    </div>
                <?php endforeach; ?>
            </fieldset>
        <?php endif; ?>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
