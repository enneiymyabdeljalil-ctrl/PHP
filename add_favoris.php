<?php
require_once __DIR__ . '/app.php';

if (!is_logged_in()) {
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
        json_response(['success' => false, 'message' => 'Authentification requise'], 401);
    }
    redirect('authentification.php');
}

$idProduit = filter_var($_POST['id_produit'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$idProduit) {
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
        json_response(['success' => false, 'message' => 'Produit invalide'], 400);
    }
    redirect($_SERVER['HTTP_REFERER'] ?? 'acceuil.php');
}

try {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id FROM favoris WHERE id_utilisateur = ? AND id_produit = ?");
    $stmt->execute([$_SESSION['idu'], $idProduit]);
    $exists = (bool) $stmt->fetchColumn();

    if ($exists) {
        $stmt = $pdo->prepare("DELETE FROM favoris WHERE id_utilisateur = ? AND id_produit = ?");
        $stmt->execute([$_SESSION['idu'], $idProduit]);
        $active = false;
        $message = 'Produit retiré des favoris';
    } else {
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoris (id_utilisateur, id_produit) VALUES (?, ?)");
        $stmt->execute([$_SESSION['idu'], $idProduit]);
        $active = true;
        $message = 'Produit ajouté aux favoris';
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE id_utilisateur = ?");
    $stmt->execute([$_SESSION['idu']]);
    $count = (int) $stmt->fetchColumn();

    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
        json_response(['success' => true, 'active' => $active, 'count' => $count, 'message' => $message]);
    }

    flash_set('success', $message);
    redirect($_SERVER['HTTP_REFERER'] ?? 'acceuil.php');
} catch (PDOException $e) {
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
        json_response(['success' => false, 'message' => 'Erreur favoris'], 500);
    }
    redirect($_SERVER['HTTP_REFERER'] ?? 'acceuil.php');
}