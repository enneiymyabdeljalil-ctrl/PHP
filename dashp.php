<?php
require_once __DIR__ . '/app.php';
require_role(['producteur']);

$id_user = $_SESSION['idu'];

try {
    $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
    $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $req_count = $c->prepare("SELECT COUNT(*) AS total FROM produit WHERE id_user = ?");
    $req_count->execute([$id_user]);
    $res_count = $req_count->fetch(PDO::FETCH_ASSOC);
    $total_produits = $res_count['total'] ?? 0;

    
    $req_cmd = $c->prepare("SELECT COUNT(DISTINCT dc.id_commande) AS total FROM ligne_commande dc JOIN produit p ON dc.id_produit = p.id WHERE p.id_user = ?");
    $req_cmd->execute([$id_user]);
    $total_commandes = $req_cmd->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    
    $req_clients = $c->prepare("SELECT COUNT(DISTINCT c.id_utilisateur) AS total FROM commande c JOIN ligne_commande dc ON c.id = dc.id_commande JOIN produit p ON dc.id_produit = p.id WHERE p.id_user = ?");
    $req_clients->execute([$id_user]);
    $total_clients = $req_clients->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    
    $req_revenus = $c->prepare("SELECT SUM(dc.prix_unitaire * dc.quantite) AS total FROM ligne_commande dc JOIN produit p ON dc.id_produit = p.id JOIN commande c ON dc.id_commande = c.id WHERE p.id_user = ? AND c.statut_paiement = 'paye'");
    $req_revenus->execute([$id_user]);
    $total_revenus = $req_revenus->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $donnees_ventes = [];
    for ($i = 5; $i >= 0; $i--) {
        $mois = date('m', strtotime("-$i months"));
        $annee = date('Y', strtotime("-$i months"));
        
        $req_mois = $c->prepare("SELECT SUM(dc.prix_unitaire * dc.quantite) AS total FROM ligne_commande dc JOIN produit p ON dc.id_produit = p.id JOIN commande c ON dc.id_commande = c.id WHERE p.id_user = ? AND MONTH(c.date_commande) = ? AND YEAR(c.date_commande) = ? AND c.statut_paiement = 'paye'");
        $req_mois->execute([$id_user, $mois, $annee]);
        $donnees_ventes[] = $req_mois->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    
    $req_cat = $c->prepare("SELECT c.nom_categorie, COUNT(p.id) AS total FROM produit p LEFT JOIN categorie c ON p.id_categorie = c.id WHERE p.id_user = ? GROUP BY c.nom_categorie");
    $req_cat->execute([$id_user]);
    $categories_res = $req_cat->fetchAll(PDO::FETCH_ASSOC);
    
    $labels_produits = [];
    $donnees_produits = [];
    foreach ($categories_res as $cat) {
        $labels_produits[] = $cat['nom_categorie'];
        $donnees_produits[] = $cat['total'];
    }
    
    
    if (empty($donnees_produits)) {
        $labels_produits = ['Aucun produit'];
        $donnees_produits = [1];
    }
    $req_table = $c->prepare("SELECT * FROM produit WHERE id_user = ? ORDER BY id DESC");
    $req_table->execute([$id_user]);
    $tous_les_produits = $req_table->fetchAll(PDO::FETCH_ASSOC);

    $req_low = $c->prepare("SELECT COUNT(*) FROM produit WHERE id_user = ? AND stock <= 5");
    $req_low->execute([$id_user]);
    $low_stock = (int) $req_low->fetchColumn();

    $req_best = $c->prepare("SELECT p.nom, COALESCE(SUM(lc.quantite), 0) AS total_vendu FROM produit p LEFT JOIN ligne_commande lc ON lc.id_produit = p.id WHERE p.id_user = ? GROUP BY p.id ORDER BY total_vendu DESC LIMIT 5");
    $req_best->execute([$id_user]);
    $best_products = $req_best->fetchAll(PDO::FETCH_ASSOC);

    $req_sat = $c->prepare("SELECT COALESCE(AVG(a.note), 0) AS satisfaction FROM avis a JOIN produit p ON p.id = a.id_produit WHERE p.id_user = ?");
    $req_sat->execute([$id_user]);
    $satisfaction = (float) $req_sat->fetchColumn();

    $req_orders = $c->prepare("SELECT co.id, co.date_commande, co.statut_commande, co.total, u.nom AS client FROM commande co JOIN utilisateur u ON u.id = co.id_utilisateur JOIN ligne_commande lc ON lc.id_commande = co.id JOIN produit p ON p.id = lc.id_produit WHERE p.id_user = ? GROUP BY co.id ORDER BY co.date_commande DESC LIMIT 20");
    $req_orders->execute([$id_user]);
    $orders_received = $req_orders->fetchAll(PDO::FETCH_ASSOC);

    $req_reviews = $c->prepare("SELECT a.*, p.nom AS produit, u.nom AS client FROM avis a JOIN produit p ON p.id = a.id_produit JOIN utilisateur u ON u.id = a.id_user WHERE p.id_user = ? ORDER BY a.date_avis DESC LIMIT 20");
    $req_reviews->execute([$id_user]);
    $reviews_received = $req_reviews->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Producteur - GreenMarket</title>
    
    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        /* MAIN CONTENT */
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

        /* STATS CARDS */
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

        /* CHARTS CONTAINER */
        .charts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 40px;
        }

        @media (max-width: 900px) {
            .charts { grid-template-columns: 1fr; }
        }

        .chart-box {
            background: white;
            padding: 25px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
        }

        .chart-box h3 {
            color: #69561F;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        /* SECTION HEADER & BUTTON */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            color: #69561F;
            margin: 0;
            font-weight: bold;
            font-size: 24px;
        }

        .btn-add {
            background-color: #69561F;
            color: #FAF1E0;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(105, 86, 31, 0.2);
        }

        .btn-add:hover {
            background-color: #544418;
            transform: translateY(-2px);
        }

        /* DESIGN TABLE */
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
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* ACTION LINKS */
        .action-link {
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px;
            transition: opacity 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .action-link.edit { color: #69561F; }
        .action-link.delete { color: #A04040; }
        .action-link:hover { opacity: 0.8; }

    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>GreenMarket</h2>
        <a href="acceuil.php"><i class="fa-solid fa-house"></i> Accueil</a>
        <a href="dashp.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
        <a href="panier.php"><i class="fa-solid fa-bag-shopping"></i> Panier</a>
        <a href="deconnexion.php" style="margin-top: 50px; color: #FAF1E0;"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        <h1 class="title">Dashboard Producteur</h1>
        <?php foreach (flash_all() as $flash): ?>
            <div style="background:white; border-radius:14px; padding:14px 18px; margin-bottom:18px; color:#69561F; font-weight:bold;">
                <?php echo h($flash['message']); ?>
            </div>
        <?php endforeach; ?>

        <!-- CARDS -->
        <div class="cards">
            <div class="card">
                <h3>Produits</h3>
                <p><?php echo $total_produits; ?></p>
            </div>
            <div class="card">
                <h3>Commandes</h3>
                <p><?php echo $total_commandes; ?></p>
            </div>
            <div class="card">
                <h3>Clients</h3>
                <p><?php echo $total_clients; ?></p>
            </div>
            <div class="card">
                <h3>Revenus</h3>
                <p><?php echo number_format($total_revenus, 2, '.', ''); ?> DH</p>
            </div>
            <div class="card">
                <h3>Stock bas</h3>
                <p><?php echo $low_stock; ?></p>
            </div>
            <div class="card">
                <h3>Satisfaction</h3>
                <p><?php echo number_format($satisfaction, 1, '.', ''); ?>/5</p>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="charts">
            <div class="chart-box">
                <h3>Ventes Mensuelles (DH)</h3>
                <canvas id="salesChart"></canvas>
            </div>
            <div class="chart-box">
                <h3>Répartition des Produits par Catégorie</h3>
                <canvas id="productsChart"></canvas>
            </div>
        </div>

        <!-- SECTION HEADER -->
        <div class="section-header">
            <h2>Gestion des Produits</h2>
            <a href="ajouter_produit.php" class="btn-add"><i class="fa-solid fa-plus"></i> Ajouter un produit</a>
        </div>

        <!-- TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tous_les_produits)): ?>
                        <?php foreach ($tous_les_produits as $prod): ?>
                        <tr>
                            <td><strong>#<?php echo $prod['id']; ?></strong></td>
                            <td>
                                <img src="imagines/<?php echo htmlspecialchars($prod['photo']); ?>" width="55" height="55" style="object-fit: cover; border-radius: 10px; border: 1px solid rgba(0,0,0,0.05);">
                            </td>
                            <td><strong><?php echo htmlspecialchars($prod['nom']); ?></strong></td>
                            <td><span style="color: #69561F; font-weight: bold;"><?php echo $prod['prix']; ?> DH</span></td>
                            <td style="color: #666; font-size: 14px; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($prod['description']); ?>
                            </td>
                            <td>
                                <a href="modifier_produit.php?id=<?php echo $prod['id']; ?>" class="action-link edit"><i class="fa-regular fa-pen-to-square"></i> Modifier</a>
                                <a href="supprimer_produit.php?id=<?php echo $prod['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')" class="action-link delete"><i class="fa-regular fa-trash-can"></i> Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #8C763F;">Aucun produit trouvé. Cliquez sur "Ajouter un produit" </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section-header">
            <h2>Commandes reçues</h2>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders_received as $order): ?>
                    <tr>
                        <td>#<?php echo (int) $order['id']; ?></td>
                        <td><?php echo h($order['client']); ?></td>
                        <td><?php echo h(date('d/m/Y', strtotime($order['date_commande']))); ?></td>
                        <td><?php echo number_format((float) $order['total'], 2, '.', ''); ?> DH</td>
                        <td><?php echo h($order['statut_commande']); ?></td>
                        <td>
                            <form action="producteur_action.php?action=update_order" method="POST" style="display:flex; gap:8px;">
                                <input type="hidden" name="id_commande" value="<?php echo (int) $order['id']; ?>">
                                <select name="statut_commande">
                                    <option value="en_preparation" <?php echo $order['statut_commande'] === 'en_preparation' ? 'selected' : ''; ?>>En préparation</option>
                                    <option value="expedie" <?php echo $order['statut_commande'] === 'expedie' ? 'selected' : ''; ?>>Expédié</option>
                                    <option value="livre" <?php echo $order['statut_commande'] === 'livre' ? 'selected' : ''; ?>>Livré</option>
                                </select>
                                <button class="action-link edit">OK</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($orders_received)): ?><tr><td colspan="6" style="text-align:center;">Aucune commande reçue.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section-header">
            <h2>Avis clients</h2>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Client</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Réponse</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews_received as $review): ?>
                    <tr>
                        <td><?php echo h($review['produit']); ?></td>
                        <td><?php echo h($review['client']); ?></td>
                        <td><?php echo (int) $review['note']; ?>/5</td>
                        <td><?php echo h($review['commentaire']); ?></td>
                        <td>
                            <form action="producteur_action.php?action=reply_review" method="POST" style="display:flex; gap:8px;">
                                <input type="hidden" name="id_avis" value="<?php echo (int) $review['id']; ?>">
                                <input name="reponse_producteur" value="<?php echo h($review['reponse_producteur'] ?? ''); ?>" placeholder="Répondre...">
                                <button class="action-link edit">OK</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($reviews_received)): ?><tr><td colspan="5" style="text-align:center;">Aucun avis.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SCRIPT CHARTS -->
    <script>
        // Bar Chart - Ventes
        const salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: <?php 
                    $labels_mois = [];
                    for ($i = 5; $i >= 0; $i--) {
                        $labels_mois[] = date('M', strtotime("-$i months"));
                    }
                    echo json_encode($labels_mois); 
                ?>,
                datasets: [{
                    label: 'Ventes (DH)',
                    data: <?php echo json_encode($donnees_ventes); ?>,
                    backgroundColor: '#69561F',
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Doughnut Chart - Produits
        const productsChart = new Chart(document.getElementById('productsChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($labels_produits); ?>,
                datasets: [{
                    data: <?php echo json_encode($donnees_produits); ?>,
                    backgroundColor: [
                        '#69561F',
                        '#8C763F',
                        '#A89665',
                        '#C4B78C',
                        '#DED5B8',
                        '#EFEAD8'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>
