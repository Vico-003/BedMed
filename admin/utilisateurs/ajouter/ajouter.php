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

// Charger les chambres disponibles
$chambres_dispo = $conn->query("SELECT id_chambre, numerochambre FROM Chambre WHERE disponible = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $identifiant = $conn->real_escape_string($_POST['identifiant']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);

    if ($type === 'patient') {
        $age = (int)$_POST['age'];
        $historique = $conn->real_escape_string($_POST['historique']);
        $motif = $conn->real_escape_string($_POST['motif']);
        $mobilite = $conn->real_escape_string($_POST['mobilite']);
        $date_entree = $_POST['date_entree'];
        $id_chambre = isset($_POST['id_chambre']) && $_POST['id_chambre'] !== '' ? (int)$_POST['id_chambre'] : "NULL";

        $sql = "INSERT INTO Patient (identifiant, mot_de_passe, nom, prenom, age, historique, motif, mobilite, date_entree, id_chambre)
                VALUES ('$identifiant', '$mot_de_passe', '$nom', '$prenom', $age, '$historique', '$motif', '$mobilite', '$date_entree', $id_chambre)";
    } elseif ($type === 'soignant') {
        $badge = $conn->real_escape_string($_POST['badge']);
        $sql = "INSERT INTO Soignant (identifiant, mot_de_passe, nom, prenom, fonction_soignant, uid_badge)
                VALUES ('$identifiant', '$mot_de_passe', '$nom', '$prenom', '', '$badge')";
    } elseif ($type === 'admin') {
        $sql = "INSERT INTO Admin (identifiant, mot_de_passe, nom, prenom)
                VALUES ('$identifiant', '$mot_de_passe', '$nom', '$prenom')";
    }

    if (isset($sql) && $conn->query($sql)) {
        // Rendre la chambre indisponible
        if (isset($id_chambre) && $id_chambre !== "NULL") {
            $conn->query("UPDATE Chambre SET disponible = 0 WHERE id_chambre = $id_chambre");
        }
        $message = "✅ Utilisateur ajouté avec succès.";
    } else {
        $message = "❌ Erreur : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="ajouter.css">

    <?php if ($message === "✅ Utilisateur ajouté avec succès.") : ?>
        <meta http-equiv="refresh" content="3;url=../utilisateurs.php">
    <?php endif; ?>

    <script>
        function afficherFormulaire() {
            const type = document.getElementById('type').value;
            document.querySelectorAll('.form-block').forEach(div => div.style.display = 'none');
            if (type) document.getElementById(type + '-form').style.display = 'block';
        }
    </script>
</head>
<body>
    <header>
        <h1>Ajouter un utilisateur</h1>
    </header>

    <nav>
        <a href="../../accueil/dashboard.php">🏠 Accueil</a>
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
        <label for="type">Type d'utilisateur</label>
        <select name="type" id="type" onchange="afficherFormulaire()" required>
            <option value="">-- Choisir --</option>
            <option value="patient">Patient</option>
            <option value="soignant">Soignant</option>
            <option value="admin">Admin</option>
        </select>

        <label>Identifiant</label>
        <input type="text" name="identifiant" required>

        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required>

        <label>Nom</label>
        <input type="text" name="nom" required>

        <label>Prénom</label>
        <input type="text" name="prenom" required>

        <!-- Patient -->
        <div id="patient-form" class="form-block" style="display:none;">
            <label>Âge</label>
            <input type="number" name="age" min="0">

            <label>Historique médical</label>
            <textarea name="historique"></textarea>

            <label>Motif d'hospitalisation</label>
            <input type="text" name="motif">

            <label>Mobilité</label>
            <input type="text" name="mobilite">

            <label>Date d’entrée</label>
            <input type="date" name="date_entree">

            <label>Chambre assignée</label>
            <select name="id_chambre">
                <option value="">-- Aucune --</option>
                <?php if ($chambres_dispo): ?>
                    <?php while ($c = $chambres_dispo->fetch_assoc()): ?>
                        <option value="<?= $c['id_chambre'] ?>">
                            Chambre <?= $c['numerochambre'] ?> (ID <?= $c['id_chambre'] ?>)
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Soignant -->
        <div id="soignant-form" class="form-block" style="display:none;">
            <label>Badge (UID)</label>
            <input type="text" name="badge">
        </div>

        <!-- Admin : rien à ajouter -->

        <button type="submit" class="btn">Ajouter</button>
    </form>
</body>
</html>