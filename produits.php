<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - GreenMarket</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        .navbar {
            background-color: #FAF1E0 !important;
            padding: 15px 0;
            border-bottom: 1px solid rgba(105, 86, 31, 0.1);
            box-shadow: none;
        }

        .navbar-brand {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            font-size: 26px;
            letter-spacing: 0.5px;
            color: #69561F !important;
        }

        .navbar-nav .nav-link {
            font-family: 'Times New Roman', Times, serif;
            color: #69561F !important;
            font-weight: bold;
            font-size: 16px; 
            text-transform: capitalize;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 18px !important;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link i {
            font-size: 14px;
            color: #69561F !important;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .navbar-nav .nav-link:hover i {
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        h1 {
            color: #69561F;
            font-weight: bold;
            font-size: 40px;
        }

        /* PRODUCT CARD DESIGN */
        .product-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative; /* تضمن تموضع الزر بشكل صحيح */
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(105, 86, 31, 0.08);
        }

        .product-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.03);
        }

        .product-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-title {
            color: #3A321A;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .product-desc {
            color: #777;
            font-size: 14px;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .price-tag {
            color: #556B2F;
            font-weight: 800;
            font-size: 22px;
            margin-bottom: 15px;
        }

        /* BUTTON GREEN */
        .btn-green {
            background-color: #69561F;
            color: #FAF1E0;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        .btn-green:hover {
            background-color: #544418;
            color: #FAF1E0;
        }

        /* --- CSS الخاص بزر المفضلة --- */
        .btn-favoris {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: rgba(255, 255, 255, 0.85);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #69561F;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .btn-favoris:hover {
            background-color: #ffffff;
            transform: scale(1.1);
            color: #dc3545;
        }

        .btn-favoris:active {
            transform: scale(0.9);
        }

        /* FOOTER */
        footer {
            background: #69561F;
            color: #FAF1E0;
            text-align: center;
            padding: 40px;
            margin-top: 80px;
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
        footer p {
            margin-bottom: 5px;
            opacity: 0.8;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <h1 class="text-center mb-5">Tous les Produits</h1>

    <div class="row g-4">

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/huile argan.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Huile d'Argan</h5>
                    <p class="product-desc">Huile naturelle pour peau et cheveux.</p>
                    <div class="price-tag">120 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/chia.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Graines de Chia</h5>
                    <p class="product-desc">Produit bio riche en protéines.</p>
                    <div class="price-tag">55 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/safran.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Safran Marocain</h5>
                    <p class="product-desc">Épice naturelle premium.</p>
                    <div class="price-tag">150 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/menthe.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Menthe Bio</h5>
                    <p class="product-desc">Herbe naturelle fraîche.</p>
                    <div class="price-tag">20 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/traditionnel.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Savon Traditionnel</h5>
                    <p class="product-desc">Savon artisanal marocain.</p>
                    <div class="price-tag">35 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/eau de rose.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Eau de Rose</h5>
                    <p class="product-desc">Tonique naturel pour le visage.</p>
                    <div class="price-tag">25 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/amber.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Parfum Ambre</h5>
                    <p class="product-desc">Parfum oriental elegant.</p>
                    <div class="price-tag">160 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/Jellaba.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Djellaba Marocaine</h5>
                    <p class="product-desc">Vêtement traditionnel artisanal.</p>
                    <div class="price-tag">350 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/tajine.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Tajine Marocain</h5>
                    <p class="product-desc">Tajine artisanal en terre.</p>
                    <div class="price-tag">170 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                <div class="overflow-hidden">
                    <img src="imagines/saccu.jpg" class="product-img">
                </div>
                <div class="product-body">
                    <h5 class="product-title">Sac Traditionnel</h5>
                    <p class="product-desc">Sac marocain en cuir naturel.</p>
                    <div class="price-tag">280 DH</div>
                    <button class="btn btn-green w-100"><i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

    </div>
</div>

<footer>
    <h4 class="fw-bold">GreenMarket</h4>
    <p>Marketplace de produits bio et du terroir marocain</p>
    <p class="small opacity-50 mt-3">©️ 2026 Tous droits réservés</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-ajouter-panier').forEach(button => {
    button.addEventListener('click', function(e) {
        
        const cartIcon = document.getElementById('cart-icon');
        const cartBadge = document.getElementById('cart-badge');
        
        if (!cartIcon) return;

        const buttonRect = this.getBoundingClientRect();
        const cartRect = cartIcon.getBoundingClientRect();

        const targetX = cartRect.left - buttonRect.left;
        const targetY = cartRect.top - buttonRect.top;

        const flyer = document.createElement('div');
        flyer.classList.add('flying-item');
        flyer.style.left = `${buttonRect.left + buttonRect.width/2 - 25}px`;
        flyer.style.top = `${buttonRect.top + buttonRect.height/2 - 25}px`;
        
        flyer.style.setProperty('--target-x', `${targetX}px`);
        flyer.style.setProperty('--target-y', `${targetY}px`);
        flyer.style.animation = 'flyToCart 0.8s forwards';

        document.body.appendChild(flyer);

        flyer.addEventListener('animationend', () => {
            flyer.remove(); 

            cartIcon.classList.add('pulse-animation');
            setTimeout(() => cartIcon.classList.remove('pulse-animation'), 400);

            let currentCount = parseInt(cartBadge.innerText) || 0;
            currentCount += 1;
            cartBadge.innerText = currentCount;
            cartBadge.classList.remove('d-none');
        });
    });
});
</script>
<script src="main.js"></script>

</body>
</html>