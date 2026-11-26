<?php
require_once __DIR__ . '/app.php';
$gmCartSummary = is_logged_in() ? cart_summary((int) $_SESSION['idu']) : ['total_quantite' => 0, 'total_prix' => '0.00'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMarket - Produits locaux et durables</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        nav {
            background-color: #69561F;
        }
        nav .navbar-brand {
            color: antiquewhite !important;
        }
        nav .navbar-nav .nav-link {
            color: antiquewhite !important;
        }
        nav .navbar-nav .nav-link:hover {
            color: #ffffff !important;
        }
        nav .navbar-nav .nav-link.text-danger {
            color: #ff6b6b !important;
        }
        nav .navbar-nav .nav-link.text-danger:hover {
            color: #ff4444 !important;
        }
        #cart-total {
            color: antiquewhite !important;
        }
        .navbar-toggler {
            border-color: antiquewhite;
        }
        .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }

        /* Style pour l'icône paramètres */
        .settings-link {
            color: antiquewhite !important;
            transition: all 0.3s ease;
        }
        .settings-link:hover {
            color: #ffffff !important;
            transform: rotate(45deg);
            display: inline-block;
        }
        
        /* Style personnalisé pour la barre de recherche intégrée */
        .search-container-header {
            max-width: 450px;
            width: 100%;
            margin: 0 20px;
        }
        .search-container-header .form-control {
            border-radius: 12px 0 0 12px;
            border: 1px solid antiquewhite;
            background-color: #FAF7F0;
        }
        .search-container-header .btn-search {
            background-color: #FAF1E0;
            color: #69561F;
            border: 1px solid antiquewhite;
            border-radius: 0 12px 12px 0;
            transition: all 0.2s;
        }
        .search-container-header .btn-search:hover {
            background-color: #ffffff;
            color: #4A3E1B;
        }
        
        @media (max-width: 991px) {
            .search-container-header {
                margin: 15px 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="imagines/logoo.jpeg" alt="Logo GreenMarket" width="46" height="46" class="rounded-circle me-2">
                <span>GreenMarket</span>
            </a>

            <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                
                <!-- LA BARRE DE RECHERCHE INTEGRÉE (S'affiche uniquement si demandée) -->
                <?php if (isset($afficher_recherche) && $afficher_recherche === true): ?>
                    <form action="recherche.php" method="GET" class="d-flex search-container-header mx-auto">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control" placeholder="Rechercher un produit ou une boutique..." required>
                            <button class="btn btn-search" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <ul class="navbar-nav ms-auto text-center align-items-center">
                    <li class="nav-item"><a class="nav-link" href="acceuil.php"><i class="fa-solid fa-house"></i> </a></li>
                    <li class="nav-item"><a class="nav-link" href="boutique.php"><i class="fa-solid fa-shop"></i> </a></li>

                    <li class="nav-item">
                        <a class="nav-link position-relative d-inline-flex align-items-center gap-1" href="panier.php" id="cart-icon">
                            <div class="position-relative">
                                <i class="fa-solid fa-basket-shopping fs-5"></i>
                                <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?php echo ($gmCartSummary['total_quantite'] > 0) ? '' : 'd-none'; ?>">
                                    <?php echo (int) $gmCartSummary['total_quantite']; ?>
                                </span>
                            </div>
                            <span style="font-size: 12px; margin-top: 2px;"></span>
                            <span id="cart-total" class="fw-bold ms-1" style="font-size: 13px;">
                                <?php echo h($gmCartSummary['total_prix']); ?> DH
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo (isset($_SESSION['idu']) && !empty($_SESSION['idu'])) ? 'profile.php' : 'authentification.php'; ?>">
                            <i class="fa-regular fa-circle-user"></i> 
                        </a>
                    </li>

                    <?php if(isset($_SESSION['idu']) && !empty($_SESSION['idu'])): ?>
                        <?php if(isset($_SESSION['roleu']) && $_SESSION['roleu'] == 'client'): ?>
                            <li class="nav-item"><a class="nav-link" href="dashc.php"><i class="fa-solid fa-chart-pie"></i> </a></li>
                        <?php elseif(isset($_SESSION['roleu']) && $_SESSION['roleu'] == 'producteur'): ?>
                            <li class="nav-item"><a class="nav-link" href="dashp.php"><i class="fa-solid fa-chart-pie"></i> </a></li>
                        <?php elseif(isset($_SESSION['roleu']) && ($_SESSION['roleu'] == 'administrateur' || $_SESSION['roleu'] == 'admin')): ?>
                            <li class="nav-item"><a class="nav-link" href="dasha.php"><i class="fa-solid fa-chart-pie"></i> </a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="authentification.php">
                                <i class="fa-solid fa-chart-pie"></i> 
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- LIEN VERS LA PAGE PARAMETRES -->
                    <li class="nav-item">
                        <a class="nav-link settings-link" href="parametres.php">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                    </li>

                    <?php if(isset($_SESSION['idu']) && !empty($_SESSION['idu'])): ?>
                        <li class="nav-item"><a class="nav-link text-danger" href="deconnexion.php"><i class="fa-solid fa-right-from-bracket"></i> </a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="authentification.php"><i class="fa-solid fa-right-to-bracket"></i> </a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
