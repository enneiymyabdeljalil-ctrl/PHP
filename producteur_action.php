<?php
require_once __DIR__ . '/app.php';
require_role(['producteur']);

$action = $_GET['action'] ?? '';
$pdo = db();

try {
    if ($action === 'update_order') {
        $idCommande = filter_var($_POST['id_commande'] ?? null, FILTER_VALIDATE_INT);
        $status = normalize_order_status((string) ($_POST['statut_commande'] ?? ''));
        if (!$idCommande || !in_array($status, ['en_preparation', 'expedie', 'livre'], true)) {
            flash_set('danger', 'Statut invalide.');
            redirect('dashp.php');
        }

        $stmt = $pdo->prepare(
            "SELECT COUNT(*)
             FROM ligne_commande lc
             JOIN produit p ON p.id = lc.id_produit
             WHERE lc.id_commande = ? AND p.id_user = ?"
        );
        $stmt->execute([$idCommande, $_SESSION['idu']]);
        if ((int) $stmt->fetchColumn() === 0) {
            flash_set('danger', 'Commande non autorisée.');
            redirect('dashp.php');
        }

        $stmt = $pdo->prepare("UPDATE commande SET statut_commande = ? WHERE id = ?");
        $stmt->execute([$status, $idCommande]);
        flash_set('success', 'Statut de commande mis à jour.');
    }

    if ($action === 'reply_review') {
        $idAvis = filter_var($_POST['id_avis'] ?? null, FILTER_VALIDATE_INT);
        $reply = trim($_POST['reponse_producteur'] ?? '');
        $stmt = $pdo->prepare(
            "UPDATE avis a
             JOIN produit p ON p.id = a.id_produit
             SET a.reponse_producteur = ?
             WHERE a.id = ? AND p.id_user = ?"
        );
        $stmt->execute([$reply, $idAvis, $_SESSION['idu']]);
        flash_set('success', 'Réponse enregistrée.');
    }
} catch (PDOException $e) {
    flash_set('danger', 'Erreur lors de l’action.');
}

redirect('dashp.php');
