<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'soignant') {
    http_response_code(403);
    exit("Accès refusé");
}

$alerteFile = '../../patient/alerte_soignant/alertes.json';
$alertes = file_exists($alerteFile) ? json_decode(file_get_contents($alerteFile), true) : [];

foreach ($alertes as $index => $alerte) {
    echo '<div class="card alerte-card">';
    echo '  <div class="card-header">';
    echo        htmlspecialchars($alerte['type']) .
               ' <span>– ' . htmlspecialchars($alerte['date']) . '</span>';
    if (!empty($alerte['from'])) {
        echo ' <span>| De : ' . htmlspecialchars($alerte['from']) . '</span>';
    }

    // ✅ Bouton AJAX
    echo '    <button type="button" class="btn-delete" data-index="' . $index . '">🗑️ Supprimer</button>';

    echo '  </div>';
    echo '  <div class="card-body">';
    echo        nl2br(htmlspecialchars($alerte['message'] ?? ''));
    echo '  </div>';
    echo '</div>';
}
