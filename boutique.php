<?php
session_start();
try {
    $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
    $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $req = $c->query("SELECT * FROM produit LIMIT 3");
    $produits = $req->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Boutiques - GreenMarket</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #FAF1E0;
            font-family: 'Times New Roman', Times, serif; 
            font-size: 16px;
            color: #3A321A;
        }
        h2 {
            font-family: 'Times New Roman', Times, serif;
            color: #69561F;
            font-weight: bold;
            font-size: 32px;
        }

        .shop-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            transition: all 0.3s ease;
            height: 100%;
        }

        .shop-card:hover {
            transform: translateY(-6px);
        }

        .shop-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .city {
            color: #69561F;
            font-weight: bold;
            font-size: 15px;
}

 .btn-green {
    background-color: #3A321A !important; 
    color: #fff !important;
    border-radius: 12px; /* Ajustez le rayon pour correspondre à "Ajouter au panier" */
    padding: 12px 20px;
    font-weight: 600;
    border: none;
    transition: 0.3s;
    text-decoration: none; /* Enlève le soulignement du lien */
    display: inline-block;
}

.btn-green:hover {
    /* Une couleur légèrement plus claire ou plus sombre au survol */
    background-color: #5a4a1f !important; 
    color: #fff !important;
    transform: scale(1.03);
}
        #search {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid rgba(105, 86, 31, 0.2);
            background-color: white;
            color: #3A321A;
        }
        
        #search:focus {
            border-color: #69561F;
            box-shadow: 0 0 0 0.25rem rgba(105, 86, 31, 0.1);
        }

        @keyframes flyToCart {
            0% {
                opacity: 1;
                transform: translate(0, 0) scale(1);
            }
            100% {
                opacity: 0.1;
                transform: translate(var(--target-x), var(--target-y)) scale(0.2);
            }
            
        }
        html,
            body {
                background: #FAF1E0 !important;
                background-color: #FAF1E0 !important;
            }

            .container {
                background: transparent !important;
            }

        .flying-item {
            position: fixed;
            z-index: 9999;
            width: 50px;
            height: 50px;
            background-color: #69561F; 
            border-radius: 50px;
            pointer-events: none;
            transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }

        @keyframes cartPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); color: #dc3545; }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: cartPulse 0.4s ease-in-out;
        }
    </style>
</head>

<body>
    <?php include("header.php")?>
    <div class="container py-5">

        <h2 class="text-center mb-4">Découvrez Nos Boutiques</h2>

        <input type="text" id="search" class="form-control mb-5" placeholder="Rechercher une boutique ou une ville...">

        <div class="row g-4" id="shops-container">

            <div class="col-md-4 boutique">
                <div class="shop-card">
                    <img src="imagines/arr1.jpg" class="shop-img">
                    <div class="p-4">
                        <h4 style="font-family: 'Times New Roman', Times, serif; font-weight: bold; color: #69561F;">Bio Nature Tetouan</h4>
                        <p class="city">📍 Tétouan</p>
                        <p>Boutique spécialisée dans les cookies bio et naturels.</p>
                        <div class="mb-3">
                            <span class="badge badge-green">Fruits Bio</span>
                            <span class="badge badge-green">Légumes</span>
                            <span class="badge badge-green">Produits Naturels</span>
                        </div>
                        <a href="bio-nature.php" class="btn btn-green w-100">Voir Boutique</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 boutique">
                <div class="shop-card">
                    <img src="imagines/arr2.jpg" class="shop-img">
                    <div class="p-4">
                        <h4 style="font-family: 'Times New Roman', Times, serif; font-weight: bold; color: #69561F;">Cosmétiques Atlas</h4>
                        <p class="city">📍 Marrakech</p>
                        <p>Produits artisanaux et cosmétiques naturels marocains.</p>
                        <div class="mb-3">
                            <span class="badge badge-green">Savon</span>
                            <span class="badge badge-green">Huiles</span>
                            <span class="badge badge-green">Beauté</span>
                        </div>
                        <a href="cosmetiques-atlas.php" class="btn btn-green w-100">Voir Boutique</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 boutique">
                <div class="shop-card">
                    <img src="imagines/arr3.jpg" class="shop-img">
                    <div class="p-4">
                        <h4 style="font-family: 'Times New Roman', Times, serif; font-weight: bold; color: #69561F;">Artisanat Casa</h4>
                        <p class="city">📍 Casablanca</p>
                        <p>Boutique artisanale spécialisée dans les produits faits main.</p>
                        <div class="mb-3">
                            <span class="badge badge-green">Bougies</span>
                            <span class="badge badge-green">Décoration</span>
                            <span class="badge badge-green">Artisanat</span>
                        </div>
                        <a href="artisanat-casa.php" class="btn btn-green w-100">Voir Boutique</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>