<?php
require_once __DIR__ . '/app.php';
require_login();

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    redirect('dashc.php');
}

$pdo = db();
$sql = "SELECT c.*, u.nom, u.prenom, u.email, u.adresse, u.telephone
        FROM commande c
        JOIN utilisateur u ON u.id = c.id_utilisateur
        WHERE c.id = ?";
$params = [$id];
if (!in_array(current_role(), ['administrateur', 'admin'], true)) {
    $sql .= " AND c.id_utilisateur = ?";
    $params[] = $_SESSION['idu'];
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$commande = $stmt->fetch();
if (!$commande) {
    redirect('dashc.php');
}

$stmt = $pdo->prepare("SELECT lc.*, p.nom FROM ligne_commande lc JOIN produit p ON p.id = lc.id_produit WHERE lc.id_commande = ?");
$stmt->execute([$id]);
$lignes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #<?php echo (int) $commande['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; padding: 40px; }
        h1 { color: #69561F; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { border-bottom: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #69561F; color: white; }
        .total { text-align: right; font-size: 22px; font-weight: bold; margin-top: 20px; }
        .btn { display: inline-block; margin-bottom: 20px; background: #69561F; color: white; padding: 10px 14px; text-decoration: none; border-radius: 8px; }
        @media print { .btn { display: none; } }
    </style>
</head>
<body>
<a href="#" class="btn" onclick="window.print(); return false;">Télécharger / Imprimer PDF</a>
<h1>Facture GreenMarket #<?php echo (int) $commande['id']; ?></h1>
<p>Date: <?php echo h(date('d/m/Y', strtotime($commande['date_commande']))); ?></p>
<p>Client: <?php echo h($commande['nom'] . ' ' . $commande['prenom']); ?><br>
Email: <?php echo h($commande['email']); ?><br>
Adresse: <?php echo h($commande['adresse']); ?><br>
Téléphone: <?php echo h($commande['telephone']); ?></p>
<table>
    <thead><tr><th>Produit</th><th>Quantité</th><th>Prix</th><th>Total</th></tr></thead>
    <tbody>
    <?php foreach ($lignes as $ligne): ?>
        <tr>
            <td><?php echo h($ligne['nom']); ?></td>
            <td><?php echo (int) $ligne['quantite']; ?></td>
            <td><?php echo number_format((float) $ligne['prix_unitaire'], 2, '.', ''); ?> DH</td>
            <td><?php echo number_format((float) $ligne['prix_unitaire'] * (int) $ligne['quantite'], 2, '.', ''); ?> DH</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="total">Total: <?php echo number_format((float) $commande['total'], 2, '.', ''); ?> DH</div>
</body>
</html>
