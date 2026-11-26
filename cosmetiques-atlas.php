<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bio Nature Tetouan - GreenMarket</title>

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
        /* SIDEBAR & DESIGN */
        .sidebar {
            background-color: white;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.03);
        }

        .category-btn {
            width: 100%;
            background-color: #69561F;
            color: #FAF1E0;
            border: none;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 12px;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
            transition: all 0.3s ease;
        }

        .category-btn:hover {
            background-color: #3A321A;
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
        .products-section {
            display: none;
        }

        .product-card {
            background-color: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            transition: all 0.3s ease;
            height: 100%;
            position: relative; /* مهم لتموضع زر المفضلة */
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

       .btn-green {
    background-color: #69561F !important; /* Votre nouvelle couleur marron */
    color: #FAF1E0 !important;
    border: none;
    border-radius: 12px;
    padding: 10px;
    font-weight: bold;
    font-family: 'Times New Roman', Times, serif;
    transition: background-color 0.2s ease;
}

.btn-green:hover {
    background-color: #4E3F16 !important; /* Un marron plus sombre pour l'effet au survol */
    color: #FAF1E0 !important;
}

        h2 {
            font-family: 'Times New Roman', Times, serif;
            color: #69561F;
            font-weight: bold;
            font-size: 32px;
            margin-bottom: 24px;
        }
        
        .text-success { 
            color: #69561F !important; 
            font-weight: bold;
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
            color: #dc3545; /* يتحول للون الأحمر عند التمرير */
        }

        .btn-favoris:active {
            transform: scale(0.9);
        }
    </style>
</head>

<body>
<?php include("header.php")?>
<div class="container py-5">
    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="sidebar">
                <h4 class="mb-4 text-success" style="font-family: 'Times New Roman', Times, serif;">Catégories</h4>
                <button class="category-btn" onclick="showProducts('tous')">Tous</button>
                <button class="category-btn" onclick="showProducts('huiles')">Huiles</button>
                <button class="category-btn" onclick="showProducts('savons')">Savons</button>
                <button class="category-btn" onclick="showProducts('eaux')">Eaux de fleurs</button>
                <button class="category-btn" onclick="showProducts('parfums')">Parfums</button>
                <button class="category-btn" onclick="showProducts('soins')">Soins visage</button>
                <button class="category-btn" onclick="showProducts('traditionnels')">Produits traditionnels</button>
            </div>
        </div>

        <div class="col-md-9">
            
            <div id="tous" class="products-section" style="display:block;">
                <h2>Tous les Produits</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/huile argan.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Huile d’Argan</h5>
                                <p>Huile bio nourrissante pour peau et cheveux.</p>
                                <h4 class="text-success">120 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/traditionnel.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Savon traditionnel</h5>
                                <p>Savon artisanal marocain doux pour la peau.</p>
                                <h4 class="text-success">35 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/eau de rose.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Eau de rose</h5>
                                <p>Tonique naturel pour rafraîchir la peau.</p>
                                <h4 class="text-success">25 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="huiles" class="products-section">
                <h2>Huiles naturelles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/huile argan.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Huile d’Argan</h5>
                                <p>Huile bio nourrissante pour peau et cheveux.</p>
                                <h4 class="text-success">120 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/huile barbarie.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Huile de Figue de Barbarie</h5>
                                <p>Huile anti-âge rare et très concentrée.</p>
                                <h4 class="text-success">180 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/huile de rose.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Huile de Rose</h5>
                                <p>Huile douce parfumée et hydratante.</p>
                                <h4 class="text-success">140 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="savons" class="products-section">
                <h2>Savons naturels</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/traditionnel.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Savon traditionnel</h5>
                                <p>Savon artisanal marocain doux pour la peau.</p>
                                <h4 class="text-success">35 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/ghasoul.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Ghasoul marocain</h5>
                                <p>Argile naturelle purifiante pour visage et corps.</p>
                                <h4 class="text-success">45 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/argile.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Argile volcanique</h5>
                                <p>Nettoyant naturel riche en minéraux.</p>
                                <h4 class="text-success">40 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="eaux" class="products-section">
                <h2>Eaux de fleurs</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/eau de rose.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Eau de rose</h5>
                                <p>Tonique naturel pour rafraîchir la peau.</p>
                                <h4 class="text-success">25 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/eau doranger.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Eau d’oranger</h5>
                                <p>Parfum naturel relaxant et apaisant.</p>
                                <h4 class="text-success">25 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="parfums" class="products-section">
                <h2>Parfums</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/amber.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Parfum ambre</h5>
                                <p>Parfum oriental chaud et luxueux.</p>
                                <h4 class="text-success">160 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/oud.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Parfum oud</h5>
                                <p>Parfum profond traditionnel marocain.</p>
                                <h4 class="text-success">200 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/musc.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Musc marocain</h5>
                                <p>Parfum doux et longue durée.</p>
                                <h4 class="text-success">150 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="soins" class="products-section">
                <h2>Soins visage</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/creme-argan.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Crème d’argan</h5>
                                <p>Hydratation profonde et naturelle.</p>
                                <h4 class="text-success">90 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/mask ghasoul.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Masque de ghasoul</h5>
                                <p>Purifie et nettoie la peau en profondeur.</p>
                                <h4 class="text-success">60 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/serum barbarie" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Sérum figue de barbarie</h5>
                                <p>Anti-âge puissant et naturel.</p>
                                <h4 class="text-success">190 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="traditionnels" class="products-section">
                <h2>Produits traditionnels</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/henne.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Henné marocain</h5>
                                <p>Coloration naturelle traditionnelle.</p>
                                <h4 class="text-success">30 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/khol.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Khol marocain</h5>
                                <p>Maquillage naturel des yeux.</p>
                                <h4 class="text-success">25 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/miswak.jpg" class="product-img">
                            <div class="p-3">
                                <h5 style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">Miswak marocain</h5>
                                <p>Hygiène dentaire naturelle.</p>
                                <h4 class="text-success">15 DH</h4>
                                <button class="btn btn-green w-100">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showProducts(category){
    const sections = document.querySelectorAll(".products-section");
    sections.forEach(s => s.style.display = "none");
    document.getElementById(category).style.display = "block";
}
document.querySelectorAll('.btn-ajouter-panier').forEach(button => {
    

    const cartIcon = document.getElementById('cart-icon');
    const cartBadge = document.getElementById('cart-badge');
    
    if (!cartIcon) return;

    
    const buttonRect = this.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();

    const targetX = cartRect.left - buttonRect.left;
    const targetY = cartRect.top - buttonRect.top;

    
    const flyer = document.createElement('div');
    flying-item.classList.add('flying-item');
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
</script>
</body>
</html>