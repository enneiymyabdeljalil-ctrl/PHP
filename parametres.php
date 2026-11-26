<?php
// parametres.php - Page d'interface des paramètres
require_once 'settings.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mettre à jour les paramètres
    if (isset($_POST['language']) && isset($available_languages[$_POST['language']])) {
        $_SESSION['settings']['language'] = $_POST['language'];
        setcookie('language', $_POST['language'], time() + 86400 * 30, '/');
    }
    
    if (isset($_POST['theme']) && in_array($_POST['theme'], ['light', 'dark'])) {
        $_SESSION['settings']['theme'] = $_POST['theme'];
        setcookie('theme', $_POST['theme'], time() + 86400 * 30, '/');
    }
    
    if (isset($_POST['currency']) && isset($available_currencies[$_POST['currency']])) {
        $_SESSION['settings']['currency'] = $_POST['currency'];
        setcookie('currency', $_POST['currency'], time() + 86400 * 30, '/');
    }
    
    if (isset($_POST['timezone'])) {
        $_SESSION['settings']['timezone'] = $_POST['timezone'];
        setcookie('timezone', $_POST['timezone'], time() + 86400 * 30, '/');
    }
    
    // Notifications
    $_SESSION['settings']['notifications'] = [
        'email' => isset($_POST['notif_email']),
        'push' => isset($_POST['notif_push']),
        'promo' => isset($_POST['notif_promo'])
    ];
    
    // Marquer que les paramètres ont été modifiés
    $_SESSION['settings_changed'] = true;
    
    // Rediriger pour éviter la resoumission du formulaire
    header('Location: parametres.php?success=1');
    exit();
}

// Récupérer les paramètres actuels
$current_language = $_SESSION['settings']['language'] ?? 'fr';
$current_theme = $_SESSION['settings']['theme'] ?? 'light';
$current_currency = $_SESSION['settings']['currency'] ?? 'MAD';
$current_timezone = $_SESSION['settings']['timezone'] ?? 'Africa/Casablanca';
$notifications = $_SESSION['settings']['notifications'] ?? ['email' => true, 'push' => true, 'promo' => false];
?>
<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>" class="<?php echo $current_theme === 'dark' ? 'dark-theme' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('settings_title'); ?> - GreenMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-color: #f8f5f0;
            --text-color: #333;
            --card-bg: #ffffff;
            --border-color: #e8e0d6;
            --shadow: rgba(0,0,0,0.08);
            --input-bg: #ffffff;
        }

        body.dark-theme {
            --bg-color: #1a1a2e;
            --text-color: #e0e0e0;
            --card-bg: #16213e;
            --border-color: #2a3a5e;
            --shadow: rgba(0,0,0,0.3);
            --input-bg: #2a3a5e;
        }

        body {
            background-color: #FAF1E0 !important;
            color: var(--text-color);
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            min-height: 100vh;
        }

        .settings-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .settings-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px var(--shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .settings-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--text-color);
        }

        .settings-subtitle {
            color: #888;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-color);
        }

        .form-control, .form-select {
            background-color: var(--input-bg);
            color: var(--text-color);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #69561F;
            box-shadow: 0 0 0 0.2rem rgba(105, 86, 31, 0.25);
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
        }

        .form-check-input:checked {
            background-color: #69561F;
            border-color: #69561F;
        }

        .form-check-label {
            color: var(--text-color);
        }

        .btn-save {
            background: #69561F;
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-save:hover {
            background: #7a6424;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(105, 86, 31, 0.3);
            color: white;
        }

        .btn-back {
            color: var(--text-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 2px solid var(--border-color);
        }

        .btn-back:hover {
            background: var(--border-color);
            color: var(--text-color);
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            border-radius: 12px;
        }

        .section-divider {
            border-top: 2px solid var(--border-color);
            margin: 30px 0;
            opacity: 0.5;
        }

        .language-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .language-option .flag {
            font-size: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .settings-card {
                padding: 20px;
            }
            .settings-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Inclure la navbar -->
    <?php include_once 'navbar.php'; ?>

    <div class="settings-container">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo __('settings_saved'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="settings-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="settings-title">
                        <i class="fas fa-gear me-3" style="color: #69561F;"></i>
                        <?php echo __('settings_title'); ?>
                    </h1>
                    <p class="settings-subtitle"><?php echo __('choose_language'); ?></p>
                </div>
                <a href="acceuil.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> <?php echo __('back'); ?>
                </a>
            </div>

            <form method="POST" action="">
                <!-- Langue -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-globe me-2"></i> <?php echo __('language'); ?>
                    </label>
                    <select name="language" class="form-select">
                        <?php foreach ($available_languages as $code => $lang): ?>
                            <option value="<?php echo $code; ?>" <?php echo $current_language === $code ? 'selected' : ''; ?>>
                                <?php echo $lang['flag']; ?> <?php echo $lang['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="section-divider"></div>

                <!-- Thème -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-moon me-2"></i> <?php echo __('theme'); ?>
                    </label>
                    <select name="theme" class="form-select">
                        <option value="light" <?php echo $current_theme === 'light' ? 'selected' : ''; ?>>
                            <i class="fas fa-sun me-2"></i> <?php echo __('light_theme'); ?>
                        </option>
                        <option value="dark" <?php echo $current_theme === 'dark' ? 'selected' : ''; ?>>
                            <i class="fas fa-moon me-2"></i> <?php echo __('dark_theme'); ?>
                        </option>
                    </select>
                </div>

                <div class="section-divider"></div>

                <!-- Devise -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-money-bill me-2"></i> <?php echo __('currency'); ?>
                    </label>
                    <select name="currency" class="form-select">
                        <?php foreach ($available_currencies as $code => $curr): ?>
                            <option value="<?php echo $code; ?>" <?php echo $current_currency === $code ? 'selected' : ''; ?>>
                                <?php echo $curr['symbol']; ?> - <?php echo $curr['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="section-divider"></div>

                <!-- Fuseau horaire -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-clock me-2"></i> <?php echo __('timezone'); ?>
                    </label>
                    <select name="timezone" class="form-select">
                        <option value="Africa/Casablanca" <?php echo $current_timezone === 'Africa/Casablanca' ? 'selected' : ''; ?>>Africa/Casablanca</option>
                        <option value="Europe/Paris" <?php echo $current_timezone === 'Europe/Paris' ? 'selected' : ''; ?>>Europe/Paris</option>
                        <option value="Europe/London" <?php echo $current_timezone === 'Europe/London' ? 'selected' : ''; ?>>Europe/London</option>
                        <option value="America/New_York" <?php echo $current_timezone === 'America/New_York' ? 'selected' : ''; ?>>America/New_York</option>
                        <option value="America/Los_Angeles" <?php echo $current_timezone === 'America/Los_Angeles' ? 'selected' : ''; ?>>America/Los_Angeles</option>
                        <option value="Asia/Dubai" <?php echo $current_timezone === 'Asia/Dubai' ? 'selected' : ''; ?>>Asia/Dubai</option>
                        <option value="Asia/Tokyo" <?php echo $current_timezone === 'Asia/Tokyo' ? 'selected' : ''; ?>>Asia/Tokyo</option>
                    </select>
                </div>

                <div class="section-divider"></div>

                <!-- Notifications -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-bell me-2"></i> <?php echo __('notifications'); ?>
                    </label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="notif_email" id="notif_email" <?php echo $notifications['email'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="notif_email">
                            <?php echo __('email_notifications'); ?>
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="notif_push" id="notif_push" <?php echo $notifications['push'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="notif_push">
                            <?php echo __('push_notifications'); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="notif_promo" id="notif_promo" <?php echo $notifications['promo'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="notif_promo">
                            <?php echo __('promo_notifications'); ?>
                        </label>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- Bouton de sauvegarde -->
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i> <?php echo __('save_settings'); ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Appliquer le thème dynamiquement
        document.addEventListener('DOMContentLoaded', function() {
            const themeSelect = document.querySelector('select[name="theme"]');
            if (themeSelect) {
                themeSelect.addEventListener('change', function() {
                    if (this.value === 'dark') {
                        document.body.classList.add('dark-theme');
                        document.documentElement.classList.add('dark-theme');
                    } else {
                        document.body.classList.remove('dark-theme');
                        document.documentElement.classList.remove('dark-theme');
                    }
                });
            }
        });
    </script>
</body>
</html>