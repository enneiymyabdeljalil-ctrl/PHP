<?php
require_once __DIR__ . '/app.php';
require_role(['client']);

$idProduit = filter_var($_POST['id_produit'] ?? null, FILTER_VALIDATE_INT);
$note = filter_var($_POST['note'] ?? null, FILTER_VALIDATE_INT);
$commentaire = trim($_POST['commentaire'] ?? '');

if (!$idProduit || !$note || $note < 1 || $note > 5) {
    flash_set('danger', 'Avis invalide.');
    redirect('dashc.php');
}

$pdo = db();
$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM commande c
     JOIN ligne_commande lc ON lc.id_commande = c.id
     WHERE c.id_utilisateur = ? AND lc.id_produit = ? AND c.statut_commande <> 'annule'"
);
$stmt->execute([$_SESSION['idu'], $idProduit]);
if ((int) $stmt->fetchColumn() === 0) {
    flash_set('danger', 'Vous devez acheter ce produit avant de laisser un avis.');
    redirect('dashc.php');
}

$stmt = $pdo->prepare("INSERT INTO avis (id_user, id_produit, note, commentaire, statut) VALUES (?, ?, ?, ?, 'publie')");
$stmt->execute([$_SESSION['idu'], $idProduit, $note, $commentaire]);
flash_set('success', 'Avis enregistré.');
redirect('dashc.php');
