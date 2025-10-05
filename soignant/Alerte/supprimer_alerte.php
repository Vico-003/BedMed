<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'soignant') {
    http_response_code(403);
    exit("Accès refusé");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $alerteFile = '../../patient/alerte_soignant/alertes.json';
    $index = (int) $_POST['index'];

    if (file_exists($alerteFile)) {
        $alertes = json_decode(file_get_contents($alerteFile), true);
        if (isset($alertes[$index])) {
            array_splice($alertes, $index, 1);
            file_put_contents($alerteFile, json_encode($alertes, JSON_PRETTY_PRINT));
            echo "ok";
            exit;
        }
    }
}

http_response_code(400);
echo "Erreur suppression";
