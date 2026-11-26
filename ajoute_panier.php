<?php
require_once __DIR__ . '/app.php';

if (!is_logged_in()) {
    json_response(['status' => 'error', 'message' => 'Authentification requise'], 401);
}

if (current_role() !== 'client') {
    json_response(['status' => 'error', 'message' => 'Seuls les clients peuvent acheter'], 403);
}

$idProduit = filter_var($_POST['id_produit'] ?? null, FILTER_VALIDATE_INT);
if (!$idProduit) {
    json_response(['status' => 'error', 'message' => 'Produit invalide'], 400);
}

try {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id, prix, stock FROM produit WHERE id = ? AND statut_publication = 'publie'");
    $stmt->execute([$idProduit]);
    $produit = $stmt->fetch();

    if (!$produit || (int) $produit['stock'] <= 0) {
        json_response(['status' => 'error', 'message' => 'Produit indisponible'], 404);
    }

    $idPanier = active_cart_id((int) $_SESSION['idu']);
    $stmt = $pdo->prepare(
        "INSERT INTO ligne_panier (id_panier, id_produit, quantite, prix_unitaire)
         VALUES (?, ?, 1, ?)
         ON DUPLICATE KEY UPDATE
            quantite = LEAST(quantite + 1, ?),
            prix_unitaire = VALUES(prix_unitaire)"
    );
    $stmt->execute([$idPanier, $idProduit, (float) $produit['prix'], (int) $produit['stock']]);

    json_response(['status' => 'success'] + cart_summary((int) $_SESSION['idu']));
} catch (PDOException $e) {
    json_response(['status' => 'error', 'message' => 'Erreur panier'], 500);
}