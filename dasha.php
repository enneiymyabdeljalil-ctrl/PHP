<?php
require_once __DIR__ . '/app.php';
require_role(['administrateur', 'admin']);

$pdo = db();
$stats = [
    'users' => (int) $pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn(),
    'producteurs' => (int) $pdo->query("SELECT COUNT(*) FROM utilisateur WHERE role = 'producteur' AND statut_compte = 'actif'")->fetchColumn(),
    'sales' => (float) $pdo->query("SELECT COALESCE(SUM(total), 0) FROM commande WHERE statut_paiement = 'paye'")->fetchColumn(),
    'reports' => (int) $pdo->query("SELECT COUNT(*) FROM avis WHERE statut = 'signale'")->fetchColumn(),
];
$users = $pdo->query("SELECT id, nom, prenom, email, role, statut_compte, date_inscription FROM utilisateur ORDER BY id DESC LIMIT 50")->fetchAll();
$products = $pdo->query("SELECT p.*, u.nom AS vendeur, c.nom_categorie FROM produit p JOIN utilisateur u ON u.id = p.id_user LEFT JOIN categorie c ON c.id = p.id_categorie ORDER BY p.id DESC LIMIT 50")->fetchAll();
$categories = $pdo->query("SELECT c.*, COUNT(p.id) AS produits FROM categorie c LEFT JOIN produit p ON p.id_categorie = c.id GROUP BY c.id ORDER BY c.nom_categorie")->fetchAll();
$reviews = $pdo->query("SELECT a.*, p.nom AS produit, u.nom AS client FROM avis a JOIN produit p ON p.id = a.id_produit JOIN utilisateur u ON u.id = a.id_user ORDER BY a.date_avis DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - GreenMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Times New Roman', Times, serif; font-size: 16px; background-color: #FAF1E0; color: #3A321A; }
        .sidebar { width: 260px; height: 100vh; background-color: #69561F; position: fixed; left: 0; top: 0; padding-top: 30px; box-shadow: 4px 0 15px rgba(0,0,0,0.05); }
        .sidebar h2 { color: #FAF1E0; text-align: center; font-weight: bold; font-size: 24px; margin-bottom: 40px; }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: rgba(250,241,224,0.8); text-decoration: none; padding: 15px 30px; font-size: 16px; font-weight: 600; transition: all 0.3s ease; }
        .sidebar a:hover, .sidebar a.active { background-color: #3A321A; color: #FAF1E0; }
        .main { margin-left: 260px; padding: 40px; }
        .title { color: #69561F; font-weight: bold; font-size: 32px; margin-bottom: 35px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 40px; }
        .card, .table-container, .panel { background: white; padding: 24px; border-radius: 20px; box-shadow: 0 10px 30px rgba(105,86,31,0.04); border: 1px solid rgba(105,86,31,0.05); }
        .card h3 { margin: 0; font-size: 16px; color: #8C763F; text-transform: uppercase; font-weight: bold; }
        .card p { font-size: 32px; font-weight: 800; color: #3A321A; margin: 12px 0 0 0; }
        .table-container { padding: 0; overflow: hidden; margin-bottom: 34px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #69561F; color: #FAF1E0; padding: 14px; text-align: left; }
        td { padding: 14px; border-bottom: 1px solid rgba(105,86,31,0.08); vertical-align: middle; }
        .section-title { color: #69561F; margin: 34px 0 16px; font-size: 24px; }
        .action-link, button.action-link { border: 0; background: transparent; cursor: pointer; text-decoration: none; font-weight: bold; margin-right: 10px; color: #69561F; }
        .danger { color: #A04040 !important; }
        .success { color: #2f6f4e !important; }
        input, textarea { padding: 10px; border-radius: 10px; border: 1px solid rgba(105,86,31,0.2); }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>GreenMarket</h2>
    <a href="acceuil.php"><i class="fa-solid fa-house"></i> Accueil</a>
    <a href="dasha.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
    <a href="profile.php"><i class="fa-regular fa-circle-user"></i> Profil</a>
    <a href="deconnexion.php" style="margin-top: 50px; color: #FAF1E0;"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
</div>
<div class="main">
    <h1 class="title">Dashboard Administrateur</h1>
    <?php foreach (flash_all() as $flash): ?>
        <div class="panel" style="margin-bottom:18px;"><?php echo h($flash['message']); ?></div>
    <?php endforeach; ?>
    <div class="cards">
        <div class="card"><h3>Total utilisateurs</h3><p><?php echo $stats['users']; ?></p></div>
        <div class="card"><h3>Producteurs actifs</h3><p><?php echo $stats['producteurs']; ?></p></div>
        <div class="card"><h3>Volume des ventes</h3><p><?php echo number_format($stats['sales'], 2, '.', ''); ?> DH</p></div>
        <div class="card"><h3>Signalements</h3><p><?php echo $stats['reports']; ?></p></div>
    </div>

    <h2 class="section-title">Gestion des utilisateurs</h2>
    <div class="table-container"><table><thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Statut</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td>#<?php echo (int) $user['id']; ?></td>
            <td><?php echo h($user['nom'] . ' ' . $user['prenom']); ?></td>
            <td><?php echo h($user['email']); ?></td>
            <td><?php echo h($user['role']); ?></td>
            <td><?php echo h($user['statut_compte']); ?></td>
            <td>
                <a class="action-link success" href="admin_action.php?action=activate_user&id=<?php echo (int) $user['id']; ?>">Valider</a>
                <a class="action-link" href="admin_action.php?action=suspend_user&id=<?php echo (int) $user['id']; ?>">Suspendre</a>
                <?php if ((int) $user['id'] !== (int) $_SESSION['idu']): ?>
                    <a class="action-link danger" href="admin_action.php?action=delete_user&id=<?php echo (int) $user['id']; ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>

    <h2 class="section-title">Gestion des produits</h2>
    <div class="table-container"><table><thead><tr><th>ID</th><th>Produit</th><th>Vendeur</th><th>Prix</th><th>Stock</th><th>Publication</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td>#<?php echo (int) $product['id']; ?></td>
            <td><?php echo h($product['nom']); ?></td>
            <td><?php echo h($product['vendeur']); ?></td>
            <td><?php echo number_format((float) $product['prix'], 2, '.', ''); ?> DH</td>
            <td><?php echo (int) $product['stock']; ?></td>
            <td><?php echo h($product['statut_publication']); ?></td>
            <td>
                <a class="action-link success" href="admin_action.php?action=approve_product&id=<?php echo (int) $product['id']; ?>">Approuver</a>
                <a class="action-link" href="admin_action.php?action=hide_product&id=<?php echo (int) $product['id']; ?>">Masquer</a>
                <a class="action-link danger" href="supprimer_produit.php?id=<?php echo (int) $product['id']; ?>" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>

    <h2 class="section-title">Catégories</h2>
    <div class="panel" style="margin-bottom:16px;">
        <form action="admin_action.php?action=save_category" method="POST" style="display:flex; gap:10px; flex-wrap:wrap;">
            <input name="nom_categorie" placeholder="Nouvelle catégorie" required>
            <input name="description" placeholder="Description">
            <button class="action-link success">Ajouter</button>
        </form>
    </div>
    <div class="table-container"><table><thead><tr><th>Catégorie</th><th>Description</th><th>Produits</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?php echo h($cat['nom_categorie']); ?></td>
            <td><?php echo h($cat['description']); ?></td>
            <td><?php echo (int) $cat['produits']; ?></td>
            <td><a class="action-link danger" href="admin_action.php?action=delete_category&id=<?php echo (int) $cat['id']; ?>" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>

    <h2 class="section-title">Modération des avis</h2>
    <div class="table-container"><table><thead><tr><th>Produit</th><th>Client</th><th>Note</th><th>Commentaire</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($reviews as $review): ?>
        <tr>
            <td><?php echo h($review['produit']); ?></td>
            <td><?php echo h($review['client']); ?></td>
            <td><?php echo (int) $review['note']; ?>/5</td>
            <td><?php echo h($review['commentaire']); ?></td>
            <td><a class="action-link danger" href="admin_action.php?action=delete_review&id=<?php echo (int) $review['id']; ?>" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</div>
</body>
</html>
