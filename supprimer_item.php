<?php
require_once __DIR__ . '/app.php';

if (!is_logged_in()) {
    json_response(['success' => false, 'message' => 'Authentification requise'], 401);
}

$idProduit = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$idProduit) {
    json_response(['success' => false, 'message' => 'Article introuvable'], 400);
}

try {
    $idPanier = active_cart_id((int) $_SESSION['idu']);
    $stmt = db()->prepare("DELETE FROM ligne_panier WHERE id_panier = ? AND id_produit = ?");
    $stmt->execute([$idPanier, $idProduit]);
    json_response(['success' => true] + cart_summary((int) $_SESSION['idu']));
} catch (PDOException $e) {
    json_response(['success' => false, 'message' => 'Erreur panier'], 500);
}