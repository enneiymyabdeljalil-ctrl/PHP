<?php
require_once __DIR__ . '/app.php';
require_role(['administrateur', 'admin']);

$action = $_GET['action'] ?? '';
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$pdo = db();

try {
    switch ($action) {
        case 'activate_user':
            $stmt = $pdo->prepare("UPDATE utilisateur SET statut_compte = 'actif' WHERE id = ?");
            $stmt->execute([$id]);
            flash_set('success', 'Utilisateur validé.');
            break;
        case 'suspend_user':
            $stmt = $pdo->prepare("UPDATE utilisateur SET statut_compte = 'suspendu' WHERE id = ? AND id <> ?");
            $stmt->execute([$id, $_SESSION['idu']]);
            flash_set('success', 'Utilisateur suspendu.');
            break;
        case 'delete_user':
            $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ? AND id <> ?");
            $stmt->execute([$id, $_SESSION['idu']]);
            flash_set('success', 'Utilisateur supprimé.');
            break;
        case 'approve_product':
            $stmt = $pdo->prepare("UPDATE produit SET statut_publication = 'publie' WHERE id = ?");
            $stmt->execute([$id]);
            flash_set('success', 'Produit approuvé.');
            break;
        case 'hide_product':
            $stmt = $pdo->prepare("UPDATE produit SET statut_publication = 'en_attente' WHERE id = ?");
            $stmt->execute([$id]);
            flash_set('success', 'Produit masqué.');
            break;
        case 'save_category':
            $nom = trim($_POST['nom_categorie'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if ($nom === '') {
                flash_set('danger', 'Nom de catégorie obligatoire.');
                break;
            }
            $stmt = $pdo->prepare("INSERT INTO categorie (nom_categorie, description) VALUES (?, ?)");
            $stmt->execute([$nom, $description]);
            flash_set('success', 'Catégorie ajoutée.');
            break;
        case 'delete_category':
            $stmt = $pdo->prepare("DELETE FROM categorie WHERE id = ?");
            $stmt->execute([$id]);
            flash_set('success', 'Catégorie supprimée.');
            break;
        case 'delete_review':
            $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
            $stmt->execute([$id]);
            flash_set('success', 'Avis supprimé.');
            break;
        default:
            flash_set('danger', 'Action inconnue.');
    }
} catch (PDOException $e) {
    flash_set('danger', 'Erreur lors de l’action.');
}

redirect('dasha.php');
