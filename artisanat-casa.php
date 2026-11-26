<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisanat Casablanca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #FAF1E0;
            font-family: 'Times New Roman'; 
            font-size: 15px;
        }
        .sidebar {
            background-color: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .category-btn {
            width: 100%;
            border: none;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 12px;
            background-color: #69561F;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }

        .category-btn:hover {
            background-color: #544418;
        }

        .products-section {
            display: none;
        }

        .product-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            height: 100%;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

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
        .btn-brown {
            background-color: #69561F !important;
            color: white !important;
            border: none !important;
            border-radius: 12px; 
            padding: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-brown:hover {
            background-color: #544418 !important; 
            color: white !important;
        }

        .text-success { 
            color: #556B2F !important; 
        }

        h2, h5 {
            color: #2B302A;
        }

        .flying-item {
            position: fixed;
            z-index: 9999;
            width: 30px;
            height: 30px;
            background-color: #69561F; 
            border-radius: 50%;
            pointer-events: none;
            transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }

        @keyframes cartPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); color: #dc3545; }
            100%{ transform: scale(1); }
        }

        .pulse-animation {
            animation: cartPulse 0.4s ease-in-out;
        }
    </style>
</head>
<body>
<?php include("header.php")?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar">
                <h4 class="mb-4 text-center">Catégories</h4>
                <button class="category-btn" onclick="showProducts('tous')">Tous</button>
                <button class="category-btn" onclick="showProducts('industries')">Industries Traditionnelles</button>
                <button class="category-btn" onclick="showProducts('textiles')">Textiles & Vêtements</button>
                <button class="category-btn" onclick="showProducts('cuir')">Articles en cuir</button>
                <button class="category-btn" onclick="showProducts('ustensiles')">Ustensiles Traditionnels</button>
            </div>
        </div>

        <div class="col-md-9">
            
            <div id="tous" class="products-section" style="display:block;">
                <h2 class="mb-4">Tous les Produits</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="1" data-nom="Miroir gravé traditionnel">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/miroire.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Miroir gravé traditionnel</h5>
                                <p class="text-muted small">Miroir artisanal marocain fait à la main.</p>
                                <h4 class="text-success fw-bold">250 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="1" data-nom="Miroir gravé traditionnel" data-prix="250">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="2" data-nom="Djellaba marocaine">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/Jellaba.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Djellaba marocaine</h5>
                                <p class="text-muted small">Vêtement traditionnel élégant marocain.</p>
                                <h4 class="text-success fw-bold">350 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="2" data-nom="Djellaba marocaine" data-prix="350">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="3" data-nom="Tajine marocain">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/tajine.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Tajine marocain</h5>
                                <p class="text-muted small">Tajine artisanal traditionnel en terre.</p>
                                <h4 class="text-success fw-bold">170 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="3" data-nom="Tajine marocain" data-prix="170">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="industries" class="products-section">
                <h2 class="mb-4">Industries Traditionnelles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="1" data-nom="Miroir gravé traditionnel">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/miroire.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Miroir gravé traditionnel</h5>
                                <p class="text-muted small">Miroir artisanal marocain fait à la main.</p>
                                <h4 class="text-success fw-bold">250 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="1" data-nom="Miroir gravé traditionnel" data-prix="250">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="4" data-nom="Cuivre gravé">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/cuivre grave.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Cuivre gravé</h5>
                                <p class="text-muted small">Décoration artisanale traditionnelle.</p>
                                <h4 class="text-success fw-bold">320 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="4" data-nom="Cuivre gravé" data-prix="320">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="5" data-nom="Zellige traditionnel">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/zellij.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Zellige traditionnel</h5>
                                <p class="text-muted small">Art marocain authentique et coloré.</p>
                                <h4 class="text-success fw-bold">180 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="5" data-nom="Zellige traditionnel" data-prix="180">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="textiles" class="products-section">
                <h2 class="mb-4">Textiles et vêtements</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="2" data-nom="Djellaba marocaine">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/Jellaba.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Djellaba marocaine</h5>
                                <p class="text-muted small">Vêtement traditionnel élégant marocain.</p>
                                <h4 class="text-success fw-bold">350 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="2" data-nom="Djellaba marocaine" data-prix="350">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="6" data-nom="Caftan marocain">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/caftan.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Caftan marocain</h5>
                                <p class="text-muted small">Tenue artisanale raffinée et moderne.</p>
                                <h4 class="text-success fw-bold">900 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="6" data-nom="Caftan marocain" data-prix="900">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="7" data-nom="Babouche traditionnelle">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/babouche.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Babouche traditionnelle</h5>
                                <p class="text-muted small">Chaussure artisanale confortable locale.</p>
                                <h4 class="text-success fw-bold">120 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="7" data-nom="Babouche traditionnelle" data-prix="120">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cuir" class="products-section">
                <h2 class="mb-4">Articles en cuir</h2>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="8" data-nom="Portefeuille en cuir">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/porte.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Portefeuille en cuir</h5>
                                <p class="text-muted small">Portefeuille marocain artisanal élégant.</p>
                                <h4 class="text-success fw-bold">140 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="8" data-nom="Portefeuille en cuir" data-prix="140">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="9" data-nom="Ceinture en cuir">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/ceinture.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Ceinture en cuir</h5>
                                <p class="text-muted small">Ceinture traditionnelle haute quality.</p>
                                <h4 class="text-success fw-bold">110 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="9" data-nom="Ceinture en cuir" data-prix="110">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="10" data-nom="Sac traditionnel">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/saccu.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Sac traditionnel</h5>
                                <p class="text-muted small">Sac artisanal marocain en cuir naturel.</p>
                                <h4 class="text-success fw-bold">280 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="10" data-nom="Sac traditionnel" data-prix="280">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="11" data-nom="Chaussures locales">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/chaussure.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Chaussures locales</h5>
                                <p class="text-muted small">Chaussures marocaines faites à la main.</p>
                                <h4 class="text-success fw-bold">220 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="11" data-nom="Chaussures locales" data-prix="220">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ustensiles" class="products-section">
                <h2 class="mb-4">Ustensiles traditionnels</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="3" data-nom="Tajine marocain">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/tajine.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Tajine marocain</h5>
                                <p class="text-muted small">Tajine artisanal traditionnel en terre.</p>
                                <h4 class="text-success fw-bold">170 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="3" data-nom="Tajine marocain" data-prix="170">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="12" data-nom="Tasses artisanales">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/tasses.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Tasses artisanales</h5>
                                <p class="text-muted small">Tasses marocaines décorées à la main.</p>
                                <h4 class="text-success fw-bold">80 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="12" data-nom="Tasses artisanales" data-prix="80">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris btn-add-to-favoris" data-id="13" data-nom="Plateau taous">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <img src="imagines/plateau.jpg" class="product-img">
                            <div class="p-3">
                                <h5 class="fw-bold">Plateau taous</h5>
                                <p class="text-muted small">Plateau artisanal marocain décoré avec motifs paon élégants.</p>
                                <h4 class="text-success fw-bold">150 DH</h4>
                                <button class="btn btn-brown w-100 btn-add-to-cart" data-id="13" data-nom="Plateau taous" data-prix="150">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function showProducts(category){
    const sections = document.querySelectorAll(".products-section");
    sections.forEach((section)=>{
        section.style.display = "none";
    });
    document.getElementById(category).style.display = "block";
}

document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        const idProduit = this.getAttribute('data-id');
        const nomProduit = this.getAttribute('data-nom');
        const prixProduit = this.getAttribute('data-prix');
        
        const formData = new FormData();
        formData.append('id_produit', idProduit);
        formData.append('nom_produit', nomProduit);
        formData.append('prix_produit', prixProduit);

        fetch('ajoute_panier.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                animateFlyingItem(e.clientX, e.clientY);
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.innerText = data.total_quantite;
                    badge.classList.remove('d-none');
                }
                const total = document.getElementById('cart-total');
                if (total) total.innerText = data.total_prix + ' DH';
            } else {
                alert("Erreur lors de l'ajout au panier.");
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});

document.querySelectorAll('.btn-add-to-favoris').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const idProduit = this.getAttribute('data-id');
        const nomProduit = this.getAttribute('data-nom');
        const icon = this.querySelector('i');
        
        const formData = new FormData();
        formData.append('id_produit', idProduit);
        formData.append('nom_produit', nomProduit);

        fetch('ajoute_favoris.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'added') {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                this.classList.add('active');
            } else if(data.status === 'removed') {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                this.classList.remove('active');
            } else {
                icon.classList.toggle('fa-regular');
                icon.classList.toggle('fa-solid');
                this.classList.toggle('active');
            }
        })
        .catch(error => {
            icon.classList.toggle('fa-regular');
            icon.classList.toggle('fa-solid');
            this.classList.toggle('active');
        });
    });
});

function animateFlyingItem(startX, startY) {
    const cartIcon = document.getElementById('cart-icon');
    if (!cartIcon) return;
    const cartRect = cartIcon.getBoundingClientRect();

    const flyer = document.createElement('div');
    flyer.className = 'flying-item';
    flyer.style.left = `${startX - 15}px`;
    flyer.style.top = `${startY - 15}px`;
    document.body.appendChild(flyer);

    const targetX = cartRect.left + (cartRect.width / 2) - startX;
    const targetY = cartRect.top + (cartRect.height / 2) - startY;

    setTimeout(() => {
        flyer.style.transform = `translate(${targetX}px, ${targetY}px) scale(0.1)`;
        flyer.style.opacity = '0';
    }, 50);

    setTimeout(() => {
        flyer.remove();
        cartIcon.classList.add('pulse-animation');
        setTimeout(() => cartIcon.classList.remove('pulse-animation'), 400);
    }, 850);
}
</script>
</body>
</html>