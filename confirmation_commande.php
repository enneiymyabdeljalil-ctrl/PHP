<?php
require_once __DIR__ . '/app.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: panier.php");
    exit;
}

$panier = cart_items((int) $_SESSION['idu']);
if (empty($panier)) {
    flash_set('danger', 'Votre panier est vide.');
    redirect('panier.php');
}

try {
    $c = db();
    $c->beginTransaction();

    $totalProduits = 0;
    foreach ($panier as $item) {
        $totalProduits += (float) $item['prix'] * (int) $item['quantite'];
    }

    $fraisLivraison = 40;
    $totalGeneral = $totalProduits + $fraisLivraison;
    $methodePaiement = $_POST['methode_paiement'] ?? 'livraison';
    $statutPaiement = $methodePaiement === 'livraison' ? 'en_attente' : 'paye';

    $stmtPaiement = $c->prepare("INSERT INTO paiement (mode_paiement, montant, statut_paiement) VALUES (?, ?, ?)");
    $stmtPaiement->execute([$methodePaiement === 'carte' ? 'carte' : 'livraison', $totalGeneral, $statutPaiement]);
    $idPaiement = (int) $c->lastInsertId();

    $stmtFacture = $c->prepare("INSERT INTO facteur (montant_facteur) VALUES (?)");
    $stmtFacture->execute([$totalGeneral]);
    $idFacture = (int) $c->lastInsertId();

    $stmtCommande = $c->prepare(
        "INSERT INTO commande (id_utilisateur, id_facteur, id_paiement, total, statut_commande, statut_paiement)
         VALUES (?, ?, ?, ?, 'en_preparation', ?)"
    );
    $stmtCommande->execute([$_SESSION['idu'], $idFacture, $idPaiement, $totalGeneral, $statutPaiement]);
    $idCommande = (int) $c->lastInsertId();

    $stmtLigne = $c->prepare(
        "INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)"
    );
    $stmtStock = $c->prepare("UPDATE produit SET stock = stock - ? WHERE id = ? AND stock >= ?");

    foreach ($panier as $item) {
        $idProduit = (int) $item['id'];
        $stmtLigne->execute([
            $idCommande,
            $idProduit,
            (int) $item['quantite'],
            (float) $item['prix']
        ]);
        $stmtStock->execute([(int) $item['quantite'], $idProduit, (int) $item['quantite']]);
        if ($stmtStock->rowCount() === 0) {
            throw new RuntimeException("Stock insuffisant pour " . $item['nom']);
        }
    }

    $idPanier = active_cart_id((int) $_SESSION['idu']);
    $stmt = $c->prepare("UPDATE panier SET statut = 'commande' WHERE id = ?");
    $stmt->execute([$idPanier]);

    $c->commit();
} catch (Throwable $e) {
    if (isset($c) && $c->inTransaction()) {
        $c->rollBack();
    }
    die("Erreur confirmation commande : " . h($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmee - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="modern.css">
</head>
<body>
<?php include("header.php"); ?>
<div class="container py-5">
        <div class="bg-white rounded-4 shadow-sm p-5 text-center mx-auto" style="max-width: 620px;">
            <h1 class="mb-3" style="color:#69561F;">Commande confirmee</h1>
            <p class="fs-5">Merci pour votre achat. Votre commande #<?php echo $idCommande; ?> a ete enregistree.</p>
            <p class="fw-bold">Total : <?php echo number_format($totalGeneral, 2, '.', ''); ?> DH</p>
            <a href="acceuil.php" class="btn btn-dark mt-3">Retour a l'accueil</a>
        </div>
    </div>
</body>
</html>