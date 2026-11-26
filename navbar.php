<?php
// navbar.php
require_once 'settings.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['settings']['language'] ?? 'fr'; ?>" class="<?php echo ($_SESSION['settings']['theme'] ?? 'light') === 'dark' ? 'dark-theme' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMarket - <?php echo __('home'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-color: #f8f5f0;
            --text-color: #333;
            --card-bg: #ffffff;
            --border-color: #e8e0d6;
            --nav-bg: #69561F;
            --nav-text: antiquewhite;
            --shadow: rgba(0,0,0,0.08);
        }

        body.dark-theme {
            --bg-color: #1a1a2e;
            --text-color: #e0e0e0;
            --card-bg: #16213e;
            --border-color: #2a3a5e;
            --shadow: rgba(0,0,0,0.3);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav {
            background-color: var(--nav-bg) !important;
        }
        nav .navbar-brand {
            color: var(--nav-text) !important;
        }
        nav .navbar-nav .nav-link {
            color: var(--nav-text) !important;
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
            color: var(--nav-text) !important;
        }
        .navbar-toggler {
            border-color: var(--nav-text);
        }
        .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }

        .settings-link {
            color: var(--nav-text) !important;
            transition: all 0.3s ease;
        }
        .settings-link:hover {
            color: #ffffff !important;
            transform: rotate(45deg);
            display: inline-block;
        }

        /* Badge de notification pour les paramètres */
        .settings-badge {
            position: relative;
        }
        .settings-badge .badge-dot {
            position: absolute;
            top: -5px;
            right: -8px;
            width: 8px;
            height: 8px;
            background-color: #ff6b6b;
            border-radius: 50%;
            border: 2px solid var(--nav-bg);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="acceuil.php">
            <img src="imagines/logoo.jpeg" alt="Logo GreenMarket" width="46" height="46" class="rounded-circle me-2">
            <span>GreenMarket</span>
        </a>

        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto text-center align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="acceuil.php" title="<?php echo __('home'); ?>">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="boutique.php" title="<?php echo __('shop'); ?>">
                        <i class="fa-solid fa-shop"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link position-relative d-inline-flex align-items-center gap-1" href="panier.php" id="cart-icon" title="<?php echo __('cart'); ?>">
                        <div class="position-relative">
                            <i class="fa-solid fa-basket-shopping fs-5"></i>
                            <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?php echo (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) ? '' : 'd-none'; ?>">
                                <?php echo isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0; ?>
                            </span>
                        </div>
                        <span id="cart-total" class="fw-bold ms-1" style="font-size: 13px;">
                            <?php 
                            if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
                                $total = array_sum(array_column($_SESSION['panier'], 'prix'));
                                echo formatPrice($total);
                            } else {
                                echo formatPrice(0);
                            }
                            ?>
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo (isset($_SESSION['idu']) && !empty($_SESSION['idu'])) ? 'profile.php' : 'authentification.php'; ?>" title="<?php echo __('profile'); ?>">
                        <i class="fa-regular fa-circle-user"></i>
                    </a>
                </li>

                <?php if(isset($_SESSION['idu']) && !empty($_SESSION['idu'])): ?>
                    <?php if(isset($_SESSION['roleu']) && $_SESSION['roleu'] == 'client'): ?>
                        <li class="nav-item"><a class="nav-link" href="dashc.php" title="<?php echo __('dashboard'); ?>"><i class="fa-solid fa-chart-pie"></i></a></li>
                    <?php elseif(isset($_SESSION['roleu']) && $_SESSION['roleu'] == 'producteur'): ?>
                        <li class="nav-item"><a class="nav-link" href="dashp.php" title="<?php echo __('dashboard'); ?>"><i class="fa-solid fa-chart-pie"></i></a></li>
                    <?php elseif(isset($_SESSION['roleu']) && ($_SESSION['roleu'] == 'administrateur' || $_SESSION['roleu'] == 'admin')): ?>
                        <li class="nav-item"><a class="nav-link" href="dasha.php" title="<?php echo __('dashboard'); ?>"><i class="fa-solid fa-chart-pie"></i></a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="authentification.php" title="<?php echo __('dashboard'); ?>">
                            <i class="fa-solid fa-chart-pie"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- LIEN VERS LA PAGE PARAMETRES -->
                <li class="nav-item settings-badge">
                    <a class="nav-link settings-link" href="parametres.php" title="<?php echo __('settings'); ?>">
                        <i class="fa-solid fa-gear"></i>
                        <?php if(isset($_SESSION['settings_changed']) && $_SESSION['settings_changed']): ?>
                            <span class="badge-dot"></span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if(isset($_SESSION['idu']) && !empty($_SESSION['idu'])): ?>
                    <li class="nav-item"><a class="nav-link text-danger" href="deconnexion.php" title="<?php echo __('logout'); ?>"><i class="fa-solid fa-right-from-bracket"></i></a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="authentification.php" title="<?php echo __('login'); ?>"><i class="fa-solid fa-right-to-bracket"></i></a></li>
                <?php endif; ?>
                
                <!-- Affichage de la langue actuelle -->
                <li class="nav-item ms-2">
                    <span class="text-light" style="font-size: 0.8rem; opacity: 0.7;">
                        <?php 
                        global $available_languages;
                        echo $available_languages[$_SESSION['settings']['language']]['flag'] ?? '🇫🇷'; 
                        ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Mise à jour du panier en temps réel
    function updateCartBadge(count, total) {
        const badge = document.getElementById('cart-badge');
        const totalSpan = document.getElementById('cart-total');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }
        if (totalSpan && total) {
            totalSpan.textContent = total;
        }
    }
</script>

</body>
</html>