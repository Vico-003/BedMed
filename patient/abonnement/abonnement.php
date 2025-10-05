<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choix de l'abonnement TV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="abonnement.css?v=1.0">
    <script src="abonnement.js" defer></script>
    <script src="abonnement.js"></script>
</head>
<body>

<header>
    <div class="username">Mon Abonnement TV</div>
    <a href="../../auth/logout.php" class="logout-icon">
        <i class="bi bi-box-arrow-right"></i>
    </a>
</header>

<div class="sidebar">
    <img src="../../images/logo bedmed.png" alt="Logo BEDMED" class="logo">
    <a href="../accueil/accueil.php" class="icon-btn maison">
        <img src="../../images/maison.png" alt="Accueil">
    </a>
    <a href="../alerte_soignant/alerte.php" class="icon-btn alerte">
        <img src="../../images/alerte.png" id="icone-alert" alt="Alerte">
    </a>
    <a href="../control_lit/control.php" class="icon-btn lit">
        <img src="../../images/lit.png" alt="lit">
    </a>
    <a href="../abonnement/abonnement.php" class="icon-btn tele">
        <img src="../../images/tv.png" alt="Abonnement">
    </a>
    <img src="../../images/logo chu.png" alt="CHU Lille" class="chu-logo">
</div>
<div class="main-container">
<main class="container abonnements">
  <div class="row">

    <!-- FORFAIT GRATUIT PAR DÉFAUT -->
    <div class="col">
      <div class="card abonnement h-100" data-id="serenite">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🅿️ Sérénité</h5>
          <p class="card-text flex-grow-1">
            Gratuit<br>
            Inclus :
            <ul>
              <li>🌐 Chaînes locales disponibles</li>
              <li>🎧 Radios nationales (France Inter, Europe 1...)</li>
              <li>❌ Pas de replay ni de contenu premium</li>
            </ul>
            Accès basique pour rester connecté à l'essentiel.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="confort">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🍿 Confort</h5>
          <p class="card-text flex-grow-1">
            2,50€/jour<br>
            Inclus :
            <ul>
              <li>🎼 Chaînes musicales et TNT complète</li>
              <li>⏱️ Accès à quelques replays</li>
              <li>📻 Radios et divertissement varié</li>
            </ul>
            Une offre équilibrée pour se divertir sans excès.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="cinema">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🎮 Cinéma</h5>
          <p class="card-text flex-grow-1">
            4€/jour<br>
            Inclus :
            <ul>
              <li>🎞️ Accès aux chaînes Ciné+ (Premier, Frisson...)</li>
              <li>🕰️ Replay films et séries 48h</li>
              <li>🌎 Séries internationales</li>
            </ul>
            Idéal pour les cinéphiles et séries addicts.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="sport">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">⚽ Sport</h5>
          <p class="card-text flex-grow-1">
            4,50€/jour<br>
            Inclus :
            <ul>
              <li>🏆 Eurosport, beIN SPORTS</li>
              <li>⏰ Replays, résumés de matchs</li>
              <li>🎮 Chaînes eSport et analyses</li>
            </ul>
            Pour les passionnés de sport en direct et en rediffusion !
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="famille">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">👨‍👩‍👧‍👦 Famille</h5>
          <p class="card-text flex-grow-1">
            3,50€/jour<br>
            Inclus :
            <ul>
              <li>👶 Chaînes enfants (Gulli, Disney...)</li>
              <li>🍳 Cuisine, loisirs, déco</li>
              <li>⌚ Replay 24h illimité</li>
            </ul>
            Parfait pour divertir toute la famille.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="culture">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🌍 Culture & Monde</h5>
          <p class="card-text flex-grow-1">
            3€/jour<br>
            Inclus :
            <ul>
              <li>🎨 Documentaires historiques</li>
              <li>🌽 Reportages monde & société</li>
              <li>📚 Chaînes linguistiques</li>
            </ul>
            Idéal pour découvrir et apprendre chaque jour.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="jeunesse">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🤭 Jeunesse+</h5>
          <p class="card-text flex-grow-1">
            2€/jour<br>
            Inclus :
            <ul>
              <li>🌟 Dessins animés et séries junior</li>
              <li>📚 Contes audio et jeux interactifs</li>
              <li>🎮 Activités éducatives</li>
            </ul>
            Pour occuper les enfants avec du contenu fun et sûr.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="decouverte">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🔍 Découverte</h5>
          <p class="card-text flex-grow-1">
            2€/jour<br>
            Inclus :
            <ul>
              <li>🌿 Nature et animaux</li>
              <li>⚖️ Sciences, technologie</li>
              <li>🌍 Planète et environnement</li>
            </ul>
            Explore le monde depuis ton lit d'hôpital.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="relax">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🧘 Relax</h5>
          <p class="card-text flex-grow-1">
            2€/jour<br>
            Inclus :
            <ul>
              <li>🌸 Chaînes zen : yoga, méditation</li>
              <li>🎶 Musique douce</li>
              <li>✨ Images apaisantes</li>
            </ul>
            Pour une journée calme et relaxante.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="retro">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📅 Rétro</h5>
          <p class="card-text flex-grow-1">
            1,50€/jour<br>
            Inclus :
            <ul>
              <li>📺 Séries 70s, 80s, 90s</li>
              <li>👨‍💼 Programmes nostalgiques</li>
              <li>⏲️ Replays vintage</li>
            </ul>
            Retour en arrière avec les classiques de la TV !
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="actualite">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📰 Actualité</h5>
          <p class="card-text flex-grow-1">
            1€/jour<br>
            Inclus :
            <ul>
              <li>📰 Chaînes infos 24h/24</li>
              <li>🤝 Débats et analyses politiques</li>
              <li>📅 Flashs spéciaux en direct</li>
            </ul>
            Toujours au courant, même à l'hôpital.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card abonnement h-100" data-id="premium">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">💎 Premium</h5>
          <p class="card-text flex-grow-1">
            6€/jour<br>
            Inclus :
            <ul>
              <li>🔑 Accès à toutes les chaînes</li>
              <li>💸 Films, séries, sport, replays illimités</li>
              <li>🎁 Bonus exclusifs (sans pub, contenu premium)</li>
            </ul>
            L'offre ultime pour tout avoir sans limite.
          </p>
          <button class="btn btn-primary mt-auto select-btn">Sélectionner</button>
        </div>
      </div>
    </div>

  </div>
</main>
</div>
<!-- POPUP -->
<div id="popup" class="popup">
    <div class="popup-content">
        <p>Souhaitez-vous vraiment annuler ce forfait ?</p>
        <button id="confirmCancel" class="btn btn-danger">Oui, annuler</button>
        <button id="cancelPopup" class="btn btn-secondary">Non</button>
    </div>
</div>

</body>
</html>