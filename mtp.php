<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - GreenMarket</title>

    <!-- Bootstrap 5.3.3 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #FAF1E0;
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

        .nav-link:hover {
            opacity: 0.8;
        }

        /* CARD DESIGN */
        .reset-card {
            border: 1px solid rgba(105, 86, 31, 0.08);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(105, 86, 31, 0.05);
            background: white;
        }

        h2 {
            color: #69561F;
            font-weight: bold;
            font-size: 28px;
        }

        .icon-header {
            color: #69561F;
            font-size: 40px;
            margin-bottom: 15px;
        }

        /* INPUTS */
        .form-label {
            font-weight: 600;
            color: #3A321A;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid rgba(105, 86, 31, 0.2);
            background-color: #FAF1E0;
            color: #3A321A;
        }

        .form-control:focus {
            border-color: #69561F;
            box-shadow: 0 0 0 0.25rem rgba(105, 86, 31, 0.15);
            background-color: #fff;
        }

        /* BUTTONS */
        .btn-green {
            background-color: #69561F;
            color: #FAF1E0;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(105, 86, 31, 0.15);
        }

        .btn-green:hover {
            background-color: #544418;
            color: #FAF1E0;
            transform: translateY(-1px);
        }

        .text-link {
            color: #69561F !important;
            transition: opacity 0.2s;
        }

        .text-link:hover {
            opacity: 0.8;
            text-decoration: underline !important;
        }
    </style>
</head>
<body>

<!-- FORMULAIRE -->
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card reset-card p-4">
                
                <div class="text-center icon-header">
                    <i class="fa-solid fa-lock-open"></i>
                </div>

                <h2 class="text-center mb-3">Mot de passe oublié</h2>
                
                <p class="text-center text-muted mb-4 small">
                    Entrez votre adresse email pour recevoir un lien de réinitialisation sécurisé.
                </p>

                <form action="" method="POST">
                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@gmail.com" required>
                    </div>

                    <!-- Bouton -->
                    <button type="submit" class="btn btn-green w-100">
                        <i class="fa-regular fa-paper-plane me-2"></i> Envoyer le lien
                    </button>
                </form>

                <!-- Retour connexion -->
                <div class="text-center mt-4">
                    <a href="authentification.php" class="text-link text-decoration-none fw-bold small">
                        <i class="fa-solid fa-arrow-left-long me-1"></i> Retour à la connexion
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="mtp.js"></script>

</body>
</html>