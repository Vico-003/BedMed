<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli(
    'bdjiqcyfkuixyubw0fdd-mysql.services.clever-cloud.com',
    'up0gqrwfyet1kt3b',
    'LjMVU9QGJFxqDutI1C7l',
    'bdjiqcyfkuixyubw0fdd',
    3306
);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // On utilisera password_verify

// Vérification dans la table Patient
$sql = "SELECT * FROM Patient WHERE identifiant = '$username'";
$result = $conn->query($sql);
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'patient';
        $_SESSION['prenom'] = $user['prenom']; // <-- Ajout du prénom
        header("Location: ../patient/accueil/accueil.php");
        exit();
    }
}

    // Vérification dans la table Soignant
    $sql = "SELECT * FROM Soignant WHERE identifiant = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'soignant';
            $_SESSION['user_id'] = $user['id']; // <-- Ajoute cette ligne
            header("Location: ../soignant/accueil/dashboard.php");
            exit();
        }
    }

    // Vérification dans la table Admin
    $sql = "SELECT * FROM Admin WHERE identifiant = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            $_SESSION['prenom'] = $user['prenom']; // ← Ajouté
            $_SESSION['nom'] = $user['nom'];       // ← Ajouté
            header("Location: ../admin/accueil/dashboard.php");
            exit();
        }
    }

    // Si aucun utilisateur trouvé
    $error = "Identifiants invalides.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion CHU - Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css?v=1.0">
</head>
<body>

<div class="login-card text-center shadow">
    <img src="../images/logo chu.png" class="logo mb-3" alt="Logo CHU">
    <h2 class="mb-3 text-primary">Connexion à BED MED</h2>
    <p class="text-muted">Service ORL - CHU de Lille</p>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3 text-start">
            <label for="username" class="form-label">Identifiant</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Identifiant" required>
        </div>

        <div class="mb-3 position-relative text-start">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control pe-5" placeholder="Mot de passe" required>
            <i class="bi bi-eye-slash-fill toggle-password" id="togglePassword" onclick="togglePassword()"></i>
        </div>

        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>

    <img src="../images/logo bedmed.png" class="mt-4" style="width: 60px;" alt="Logo projet">
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("togglePassword");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("bi-eye-slash-fill");
        toggleIcon.classList.add("bi-eye-fill");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("bi-eye-fill");
        toggleIcon.classList.add("bi-eye-slash-fill");
    }
}
</script>

</body>
</html>
