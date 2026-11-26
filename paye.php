<?php
require_once __DIR__ . '/app.php';
require_login();
try {
    $c = db();

    $id_user = $_SESSION['idu'];
    $req_user = $c->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $req_user->execute([$id_user]);
    $user = $req_user->fetch(PDO::FETCH_ASSOC);

    $produits = cart_items((int) $_SESSION['idu']);
    if (empty($produits)) {
        flash_set('danger', 'Votre panier est vide.');
        redirect('panier.php');
    }

    $total_produits = 0;
    foreach ($produits as $p) {
        $total_produits += (float) $p['prix'] * (int) $p['quantite'];
    }
    
    $frais_livraison = 40;
    $total_general = $total_produits + $frais_livraison;

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - GreenMarket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: #FAF1E0;
            font-family: 'Times New Roman', Times, serif; 
            font-size: 16px;
            color: #3A321A;
        }

        /* NAVBAR */
        .navbar {
            background-color: #69561F;
            padding: 12px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .navbar-brand, .nav-link {
            color: #FAF1E0 !important;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        /* HEADER */
        .checkout-header {
            text-align: center;
            padding: 50px 20px 30px;
        }

        .checkout-header h1 {
            font-size: 42px;
            font-weight: bold;
            color: #69561F;
            margin-bottom: 10px;
        }

        .checkout-header p {
            color: #8C763F;
            font-size: 18px;
        }

        /* CHECKOUT BOX */
        .checkout-box {
            background: white;
            padding: 35px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
        }

        .checkout-box h3 {
            color: #69561F;
            margin-bottom: 25px;
            font-weight: bold;
            font-size: 22px;
        }

        /* INPUTS */
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid rgba(105, 86, 31, 0.2);
            background-color: #FAF1E0;
            color: #3A321A;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(105, 86, 31, 0.15);
            border-color: #69561F;
            background-color: #fff;
        }

        /* SUMMARY BOX */
        .summary {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
            position: sticky;
            top: 30px;
        }

        .summary h3 {
            color: #69561F;
            font-weight: bold;
            font-size: 22px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #555;
            font-size: 15px;
        }

        .total {
            border-top: 1px dashed rgba(105, 86, 31, 0.3);
            padding-top: 15px;
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #69561F;
        }

        /* PAYMENT METHODS */
        .payment-method {
            border: 1px solid rgba(105, 86, 31, 0.15);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 15px;
            transition: all 0.2s ease;
            cursor: pointer;
            background-color: #FAF1E0;
        }

        .payment-method:hover {
            border-color: #69561F;
            background: rgba(105, 86, 31, 0.05);
        }

        .payment-method input[type="radio"]:checked + div {
            font-weight: bold;
        }

        .payment-method i {
            font-size: 26px;
            color: #69561F;
            width: 35px;
            text-align: center;
        }

        .payment-method h5 {
            margin: 0;
            font-size: 16px;
            color: #3A321A;
            font-weight: 600;
        }

        .payment-method small {
            color: #777;
        }

        /* BUTTON */
        .btn-pay {
            background: #69561F;
            color: #FAF1E0;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(105, 86, 31, 0.2);
        }

        .btn-pay:hover {
            background: #544418;
            transform: translateY(-2px);
        }

        /* FOOTER */
        footer {
            background: #69561F;
            color: #FAF1E0;
            text-align: center;
            padding: 40px;
            margin-top: 80px;
        }
        footer p {
            margin-bottom: 5px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
<section class="checkout-header">
    <h1>Paiement sécurisé <i class="fa-solid fa-shield-halved ms-1" style="font-size: 30px;"></i></h1>
    <p>Finalisez votre commande GreenMarket en toute confiance</p>
</section>

<div class="container pb-5">
    <form action="confirmation_commande.php" method="POST">
        <div class="row g-4">
            
            <div class="col-lg-8">
                <div class="checkout-box">
                    <h3>Informations de livraison</h3>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required value="<?php echo htmlspecialchars(isset($user['prenom']) ? $user['prenom'] : ''); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required value="<?php echo htmlspecialchars($user['nom']); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Adresse Email</label>
                            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" required value="<?php echo htmlspecialchars(isset($user['telephone']) ? $user['telephone'] : ''); ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Adresse complète</label>
                            <input type="text" name="adresse" class="form-control" required value="<?php echo htmlspecialchars(isset($user['adresse']) ? $user['adresse'] : ''); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ville</label>
                            <select name="ville" class="form-select">
                                <option <?php echo (isset($user['ville']) && $user['ville'] == 'Tétouan') ? 'selected' : ''; ?>>Tétouan</option>
                                <option <?php echo (isset($user['ville']) && $user['ville'] == 'Casablanca') ? 'selected' : ''; ?>>Casablanca</option>
                                <option <?php echo (isset($user['ville']) && $user['ville'] == 'Rabat') ? 'selected' : ''; ?>>Rabat</option>
                                <option <?php echo (isset($user['ville']) && $user['ville'] == 'Tanger') ? 'selected' : ''; ?>>Tanger</option>
                                <option <?php echo (isset($user['ville']) && $user['ville'] == 'Marrakech') ? 'selected' : ''; ?>>Marrakech</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="code_postal" class="form-control" value="<?php echo htmlspecialchars(isset($user['code_postal']) ? $user['code_postal'] : ''); ?>">
                        </div>
                    </div>

                    <h3 class="mt-5">Méthode de paiement</h3>

                    <label class="payment-method d-flex align-items-center gap-3 w-100">
                        <input type="radio" name="methode_paiement" value="carte" checked class="form-check-input m-0">
                        <i class="bi bi-credit-card"></i>
                        <div>
                            <h5>Carte bancaire</h5>
                            <small>Visa / Mastercard</small>
                        </div>
                    </label>

                    <label class="payment-method d-flex align-items-center gap-3 w-100">
                        <input type="radio" name="methode_paiement" value="livraison" class="form-check-input m-0">
                        <i class="bi bi-cash"></i>
                        <div>
                            <h5>Paiement à la livraison</h5>
                            <small>Payer en espèces à réception</small>
                        </div>
                    </label>

                    <label class="payment-method d-flex align-items-center gap-3 w-100">
                        <input type="radio" name="methode_paiement" value="mobile" class="form-check-input m-0">
                        <i class="bi bi-phone"></i>
                        <div>
                            <h5>Mobile Banking</h5>
                            <small>CMI / Application bancaire</small>
                        </div>
                    </label>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary">
                    <h3 class="mb-4">Votre commande</h3>

                    <?php foreach ($produits as $p): ?>
                    <div class="summary-item">
                        <span><?php echo htmlspecialchars($p['nom']); ?> x <?php echo (int) $p['quantite']; ?></span>
                        <span class="fw-bold"><?php echo number_format((float) $p['prix'] * (int) $p['quantite'], 2, '.', ''); ?> DH</span>
                    </div>
                    <?php endforeach; ?>

                    <div class="summary-item">
                        <span>Livraison</span>
                        <span class="fw-bold"><?php echo $frais_livraison; ?> DH</span>
                    </div>

                    <div class="total d-flex justify-content-between">
                        <span>Total</span>
                        <span><?php echo $total_general; ?> DH</span>
                    </div>

                    <button type="submit" class="btn-pay mt-4">
                        <i class="fa-solid fa-lock me-2"></i> Confirmer le paiement
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<footer>
    <h4 class="fw-bold">GreenMarket</h4>
    <p>Marketplace de produits bio et du terroir marocain</p>
    <p class="small opacity-50 mt-3">© 2026 Tous droits réservés</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>