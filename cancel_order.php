<?php
require_once __DIR__ . '/app.php';
require_role(['client']);

$id = filter_var($_POST['id_commande'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    flash_set('danger', 'Commande invalide.');
    redirect('dashc.php');
}

$stmt = db()->prepare("UPDATE commande SET statut_commande = 'annule', statut_paiement = 'annule' WHERE id = ? AND id_utilisateur = ? AND statut_commande = 'en_preparation'");
$stmt->execute([$id, $_SESSION['idu']]);
flash_set($stmt->rowCount() ? 'success' : 'danger', $stmt->rowCount() ? 'Commande annulée.' : 'Cette commande ne peut plus être annulée.');
redirect('dashc.php');
