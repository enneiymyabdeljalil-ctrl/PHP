<?php
require_once __DIR__ . '/app.php';
require_role(['client']);

try {
    $c = db();
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$id_client = $_SESSION['idu'];

try {
    $reqUser = $c->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $reqUser->execute([$id_client]);
    $clientInfo = $reqUser->fetch(PDO::FETCH_ASSOC);

    $reqCountCmd = $c->prepare("SELECT COUNT(*) as total FROM commande WHERE id_utilisateur = ?");
    $reqCountCmd->execute([$id_client]);
    $totalCommandes = $reqCountCmd->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $reqSumCmd = $c->prepare("SELECT SUM(total) as total_depense FROM commande WHERE id_utilisateur = ? AND statut_paiement = 'paye'");
    $reqSumCmd->execute([$id_client]);
    $totalDepense = $reqSumCmd->fetch(PDO::FETCH_ASSOC)['total_depense'] ?? 0;

    $reqFav = $c->prepare("SELECT COUNT(*) as total FROM favoris WHERE id_utilisateur = ?");
    $reqFav->execute([$id_client]);
    $totalFavoris = $reqFav->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $reqCmdList = $c->prepare("SELECT c.id, c.date_commande, c.statut_commande, c.total, p.id AS id_produit, p.nom AS nom_produit FROM commande c JOIN ligne_commande dc ON c.id = dc.id_commande JOIN produit p ON dc.id_produit = p.id WHERE c.id_utilisateur = ? ORDER BY c.date_commande DESC LIMIT 10");
    $reqCmdList->execute([$id_client]);
    $commandes = $reqCmdList->fetchAll(PDO::FETCH_ASSOC);

    $reqFavList = $c->prepare("SELECT p.nom, p.prix FROM favoris f JOIN produit p ON p.id = f.id_produit WHERE f.id_utilisateur = ? ORDER BY f.date_ajout DESC LIMIT 5");
    $reqFavList->execute([$id_client]);
    $favoris = $reqFavList->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $totalCommandes = 5;
    $totalFavoris = 12;
    $totalDepense = 850;
    $commandes = [];
    $clientInfo = [
        'nom' => $_SESSION['nomu'] ?? 'Client',
        'prenom' => '',
        'email' => 'client@gmail.com',
        'ville' => 'Tétouan'
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - GreenMarket</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Times New Roman', Times, serif; 
            font-size: 16px;
            background-color: #FAF1E0;
            color: #3A321A;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: #69561F;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 30px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            font-family: 'Times New Roman', Times, serif;
            color: #FAF1E0;
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 40px;
            letter-spacing: 0.5px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(250, 241, 224, 0.8);
            text-decoration: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .sidebar a i {
            font-size: 18px;
            width: 25px;
        }

        .sidebar a:hover {
            background-color: #544418;
            color: #FAF1E0;
            padding-left: 35px;
        }

        .sidebar a.active {
            background-color: #3A321A;
            color: #FAF1E0;
            border-left: 4px solid #FAF1E0;
        }

        .main {
            margin-left: 260px;
            padding: 40px;
        }

        .title {
            font-family: 'Times New Roman', Times, serif;
            color: #69561F;
            font-weight: bold;
            font-size: 32px;
            margin-bottom: 35px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0;
            font-size: 16px;
            color: #8C763F;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }

        .card p {
            font-size: 32px;
            font-weight: 800;
            color: #3A321A;
            margin: 12px 0 0 0;
        }

        .table-container {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #69561F;
            color: #FAF1E0;
            padding: 18px;
            font-weight: 600;
            text-align: left;
        }

        td {
            padding: 18px;
            border-bottom: 1px solid rgba(105, 86, 31, 0.08);
            color: #3A321A;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
        .status.delivered { background-color: #E2F0D9; color: #385723; }
        .status.pending { background-color: #FFF2CC; color: #7F6000; }
        .status.shipped { background-color: #DDEBF7; color: #1F4E78; }

        .profile {
            background: white;
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
        }

        .profile h3 {
            color: #69561F;
            font-weight: bold;
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #FAF1E0;
            padding-bottom: 10px;
        }

        .profile p {
            font-size: 16px;
            margin: 12px 0;
            color: #555;
        }

        .profile strong {
            color: #3A321A;
            width: 100px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>GreenMarket</h2>
        <a href="acceuil.php"><i class="fa-solid fa-house"></i> Accueil</a>
        <a href="boutique.php"><i class="fa-solid fa-shop"></i> Boutique</a>
        <a href="panier.php"><i class="fa-solid fa-bag-shopping"></i> Mon Panier</a>
        <a href="dashc.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
        <a href="profile.php"><i class="fa-regular fa-circle-user"></i> Profil</a>
        <a href="deconnexion.php" style="margin-top: 50px; color: #FAF1E0;"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
    </div>

    <div class="main">
        <h1 class="title">Bienvenue <?php echo htmlspecialchars($clientInfo['nom'] . ' ' . $clientInfo['prenom']); ?> 👋</h1>
        <?php foreach (flash_all() as $flash): ?>
            <div style="background:white; border-radius:14px; padding:14px 18px; margin-bottom:18px; color:#69561F; font-weight:bold;">
                <?php echo h($flash['message']); ?>
            </div>
        <?php endforeach; ?>

        <div class="cards">
            <div class="card">
                <h3>Commandes</h3>
                <p><?php echo $totalCommandes; ?></p>
            </div>
            <div class="card">
                <h3>Produits Favoris</h3>
                <p><?php echo $totalFavoris; ?></p>
            </div>
            <div class="card">
                <h3>Total Dépensé</h3>
                <p><?php echo $totalDepense; ?> DH</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($commandes)): ?>
                        <?php foreach ($commandes as $cmd): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cmd['nom_produit']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($cmd['date_commande']))); ?></td>
                                <td>
                                    <?php if ($cmd['statut_commande'] == 'livre'): ?>
                                        <span class="status delivered">Livré</span>
                                    <?php elseif ($cmd['statut_commande'] == 'en_preparation'): ?>
                                        <span class="status pending">En préparation</span>
                                    <?php else: ?>
                                        <span class="status shipped">Expédié</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="facture.php?id=<?php echo (int) $cmd['id']; ?>" style="color:#69561F; font-weight:bold;">Facture</a>
                                    <?php if ($cmd['statut_commande'] == 'en_preparation'): ?>
                                        <a href="cancel_order.php?id=<?php echo (int) $cmd['id']; ?>" style="color:#A04040; font-weight:bold; margin-left:10px;" onclick="return confirm('Annuler cette commande ?')">Annuler</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <form action="avis_action.php" method="POST" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                        <input type="hidden" name="id_produit" value="<?php echo (int) $cmd['id_produit']; ?>">
                                        <select name="note">
                                            <option value="5">5/5</option><option value="4">4/5</option><option value="3">3/5</option><option value="2">2/5</option><option value="1">1/5</option>
                                        </select>
                                        <input name="commentaire" placeholder="Laisser un avis..." style="flex:1; min-width:220px;">
                                        <button style="background:#69561F; color:white; border:0; border-radius:8px; padding:8px 12px;">Envoyer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>Huile d'Olive</td>
                            <td>18/05/2026</td>
                            <td><span class="status delivered">Livré</span></td>
                        </tr>
                        <tr>
                            <td>Savon Naturel</td>
                            <td>19/05/2026</td>
                            <td><span class="status pending">En préparation</span></td>
                        </tr>
                        <tr>
                            <td>Fraises Bio</td>
                            <td>20/05/2026</td>
                            <td><span class="status shipped">Expédié</span></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="profile">
            <h3><i class="fa-regular fa-address-card"></i> Informations Client</h3>
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($clientInfo['nom'] . ' ' . $clientInfo['prenom']); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($clientInfo['email']); ?></p>
            <p><strong>Ville :</strong> <?php echo htmlspecialchars($clientInfo['ville'] ?? 'Non spécifiée'); ?></p>
        </div>

        <div class="profile" style="margin-top: 30px;">
            <h3><i class="fa-regular fa-heart"></i> Produits Favoris</h3>
            <?php if (empty($favoris)): ?>
                <p>Aucun produit favori pour le moment.</p>
            <?php else:?>
            <?php foreach ($favoris as $fav): ?>
                <p><strong><?php echo h($fav['nom']); ?></strong> <?php echo number_format((float) $fav['prix'], 2, '.', ''); ?> DH</p>
            <?php endforeach; ?>
            <?php endif;?>
        </div>
    </div>

</body>
</html>
