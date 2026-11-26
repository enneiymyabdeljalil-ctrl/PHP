<?php
require_once __DIR__ . '/app.php';
require_login();

try {
    $c = db();

    $id_user = $_SESSION['idu'];
    $req = $c->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $req->execute([$id_user]);
    $user = $req->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: deconnexion.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GreenMarket</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Times New Roman', Times, serif; 
            font-size: 16px;
            background-color: #FAF1E0;
            color: #3A321A;
        }

        .profile-container {
            max-width: 800px;
            margin: 60px auto;
            background-color: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.08);
            overflow: hidden;
            border: 1px solid rgba(105, 86, 31, 0.05);
        }

        .profile-header {
            background-color: #69561F;
            color: white;
            text-align: center;
            padding: 50px 20px 40px 20px;
            position: relative;
        }

        .btn-retour {
            position: absolute;
            left: 25px;
            top: 25px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 8px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.2s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-retour:hover {
            background-color: white;
            color: #69561F;
        }

        .profile-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .profile-header h1 {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-role {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            background-color: rgba(255, 255, 255, 0.2);
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 500;
        }

        .profile-content {
            padding: 40px;
        }

        .info-row {
            margin-bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(105, 86, 31, 0.12);
            padding: 20px 0;
        }

        .info-row:last-of-type {
            border-bottom: none;
        }

        .info-label {
            color: #69561F;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #3A321A;
            font-size: 17px;
            margin: 0;
        }

        .btn-action-edit {
            background-color: transparent;
            color: #69561F;
            border: 1px solid rgba(105, 86, 31, 0.3);
            padding: 6px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-action-edit:hover {
            background-color: #69561F;
            color: white;
            border-color: #69561F;
        }

        .footer-buttons {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .btn-custom {
            padding: 12px 35px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            width: 100%;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        .btn-logout:hover {
            background-color: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.3);
        }
    </style>
</head>
<body>

    <div class="profile-container">

        <div class="profile-header">
            <a href="acceuil.php" class="btn-retour">
                <i class="fa-solid fa-house"></i> Accueil
            </a>

            <img src="imagines/<?php echo (!empty($user['photo'])) ? htmlspecialchars($user['photo']) : 'pr.jpg'; ?>" alt="Photo de Profil" class="profile-img">

            <h1><?php echo htmlspecialchars($user['nom'] . ' ' . ($user['prenom'] ?? '')); ?></h1>
            <span class="profile-role">
                <i class="fa-solid fa-user-shield me-1"></i> <?php echo htmlspecialchars($user['role'] ?? $_SESSION['roleu']); ?>
            </span>
        </div>

        <div class="profile-content">
            <?php foreach (flash_all() as $flash): ?>
                <div class="alert alert-<?php echo h($flash['type']); ?>"><?php echo h($flash['message']); ?></div>
            <?php endforeach; ?>

            <div class="info-row">
                <div>
                    <div class="info-label"><i class="fa-solid fa-envelope"></i> Email</div>
                    <p class="info-value"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <a href="modifier_profil.php" class="btn-action-edit">Modifier</a>
            </div>

            <div class="info-row">
                <div>
                    <div class="info-label"><i class="fa-solid fa-phone"></i> Téléphone</div>
                    <p class="info-value"><?php echo htmlspecialchars($user['telephone'] ?? 'Non spécifié'); ?></p>
                </div>
                <a href="modifier_profil.php" class="btn-action-edit">Modifier</a>
            </div>

            <div class="info-row">
                <div>
                    <div class="info-label"><i class="fa-solid fa-location-dot"></i> Ville</div>
                    <p class="info-value"><?php echo htmlspecialchars($user['ville'] ?? 'Tétouan, Maroc'); ?></p>
                </div>
                <a href="modifier_profil.php" class="btn-action-edit">Modifier</a>
            </div>

            <div class="info-row">
                <div>
                    <div class="info-label"><i class="fa-solid fa-lock"></i> Mot de passe</div>
                    <p class="info-value">••••••••</p>
                </div>
                <a href="modifier_profil.php" class="btn-action-edit">Modifier</a>
            </div>

            <div class="info-row">
                <div>
                    <div class="info-label"><i class="fa-solid fa-calendar-days"></i> Date d'inscription</div>
                    <p class="info-value">
                        <?php 
                            echo isset($user['date_inscription']) 
                                ? htmlspecialchars(date('d/m/Y', strtotime($user['date_inscription']))) 
                                : date('d/m/Y'); 
                        ?>
                    </p>
                </div>
            </div>

            <div class="footer-buttons">
                <a href="deconnexion.php" class="btn-custom btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Se déconnecter de GreenMarket
                </a>
            </div>

        </div>

    </div>

</body>
</html>
