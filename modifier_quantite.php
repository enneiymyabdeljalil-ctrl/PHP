<?php
require_once __DIR__ . '/app.php';

if (!is_logged_in()) {
    json_response(['success' => false, 'message' => 'Authentification requise'], 401);
}

$idProduit = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$delta = filter_var($_POST['delta'] ?? 0, FILTER_VALIDATE_INT);
if (!$idProduit || $delta === false || $delta === 0) {
    json_response(['success' => false, 'message' => 'Article invalide'], 400);
}

try {
    $pdo = db();
    $idPanier = active_cart_id((int) $_SESSION['idu']);

    $stmt = $pdo->prepare(
        "SELECT lp.quantite, p.stock
         FROM ligne_panier lp
         JOIN produit p ON p.id = lp.id_produit
         WHERE lp.id_panier = ? AND lp.id_produit = ?"
    );
    $stmt->execute([$idPanier, $idProduit]);
    $row = $stmt->fetch();
    if (!$row) {
        json_response(['success' => false, 'message' => 'Article introuvable'], 404);
    }

    $quantite = max(0, min((int) $row['stock'], (int) $row['quantite'] + $delta));
    if ($quantite <= 0) {
        $stmt = $pdo->prepare("DELETE FROM ligne_panier WHERE id_panier = ? AND id_produit = ?");
        $stmt->execute([$idPanier, $idProduit]);
    } else {
        $stmt = $pdo->prepare("UPDATE ligne_panier SET quantite = ? WHERE id_panier = ? AND id_produit = ?");
        $stmt->execute([$quantite, $idPanier, $idProduit]);
    }

    json_response(['success' => true, 'quantite' => $quantite] + cart_summary((int) $_SESSION['idu']));
} catch (PDOException $e) {
    json_response(['success' => false, 'message' => 'Erreur panier'], 500);
}