<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMarket - Boutique</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght=0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
body {
    background-color: #FAF1E0;
    font-family: 'Times New Roman', Times, serif; 
    font-size: 16px;
    color: #3A321A;
}

.sidebar {
    background-color: white;
    border-radius: 24px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
}

.category-btn {
    width: 100%;
    background-color: #69561F;
    color: #FAF1E0;
    border: none;
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 15px;
    font-weight: bold;
    font-family: 'Times New Roman', Times, serif;
    transition: 0.3s;
}

.category-btn:hover {
    background-color: #3A321A;
    color: #FAF1E0;
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
    display: flex;
    flex-direction: column;
    position: relative; /* لتثبيت زر المفضلة فوق الصورة */
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-img {
    width: 100%;
    height: 220px;
    object-fit: contain;
    background-color: #fcfbfa;
    padding: 15px;
}

.product-details {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-grow: 1;
}

.btn-green {
    background-color: #69561F !important; 
    color: #FAF1E0 !important;
    border: none;
    border-radius: 12px;
    padding: 10px;
    font-weight: bold;
    font-family: 'Times New Roman', Times, serif;
    transition: background-color 0.2s ease;
}

.btn-green:hover {
    background-color: #4E3F16 !important; 
    color: #FAF1E0 !important;
}

/* --- التنسيق الدائري الجديد للمفضلة كما في الصورة المرفقة --- */
.btn-favoris {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.9);
    color: #69561F;
    border: 1px solid rgba(105, 86, 31, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 18px;
    transition: all 0.2s ease;
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.btn-favoris:hover {
    background-color: #69561F;
    color: #FAF1E0 !important;
    transform: scale(1.1);
}

h2 {
    font-family: 'Times New Roman', Times, serif;
    color: #69561F;
    font-weight: bold;
    font-size: 32px;
}

h5 {
    font-family: 'Times New Roman', Times, serif;
    color: #69561F;
    font-weight: bold;
    font-size: 22px;
}

p {
    color: #7A725D;
    font-size: 15px;
}

.text-success {
    color: #556B2F !important;
    font-weight: bold;
    font-size: 24px;
    margin-bottom: 15px;
}
    </style>
</head>

<body>
    <?php include("header.php")?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="sidebar">
                <h4 class="mb-4 text-success" style="font-family: 'Times New Roman', Times, serif;">Categories</h4>
                <button class="category-btn" onclick="showProducts('tous')">Tous</button>
                <button class="category-btn" onclick="showProducts('huiles')">Huiles</button>
                <button class="category-btn" onclick="showProducts('graines')">Graines</button>
                <button class="category-btn" onclick="showProducts('epices')">Epices</button>
                <button class="category-btn" onclick="showProducts('herbes')">Herbes</button>
            </div>
        </div>

        <div class="col-md-9">
            
            <!-- SECTION: TOUS -->
            <div id="tous" class="products-section" style="display:block;">
                <h2 class="mb-4">Tous les Produits</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/Aceite Vegetal.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Huile d'Olive</h5>
                                <p>Huile extra vierge bio.</p>
                                <h4 class="text-success">95 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="2">
                                    <input type="hidden" name="nom_produit" value="Huile d'Olive">
                                    <input type="hidden" name="prix_produit" value="95">
                                    <input type="hidden" name="photo_produit" value="Aceite Vegetal.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/chia.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Graines de Chia</h5>
                                <p>Graines naturelles riches en nutriments.</p>
                                <h4 class="text-success">85 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="7">
                                    <input type="hidden" name="nom_produit" value="Graines de Chia">
                                    <input type="hidden" name="prix_produit" value="85">
                                    <input type="hidden" name="photo_produit" value="chia.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/comin.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Cumin</h5>
                                <p>Épice naturelle marocaine.</p>
                                <h4 class="text-success">35 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="8">
                                    <input type="hidden" name="nom_produit" value="Cumin">
                                    <input type="hidden" name="prix_produit" value="35">
                                    <input type="hidden" name="photo_produit" value="comin.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION: HUILES -->
            <div id="huiles" class="products-section">
                <h2 class="mb-4">Huiles Naturelles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/Aceite Vegetal.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Huile d'Olive</h5>
                                <p>Huile extra vierge bio.</p>
                                <h4 class="text-success">95 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="2">
                                    <input type="hidden" name="nom_produit" value="Huile d'Olive">
                                    <input type="hidden" name="prix_produit" value="95">
                                    <input type="hidden" name="photo_produit" value="Aceite Vegetal.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/HUILE D'AMANDE  DOUCE.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Huile d'Amandes</h5>
                                <p>Huile naturelle pure et artisanal.</p>
                                <h4 class="text-success">120 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="9">
                                    <input type="hidden" name="nom_produit" value="Huile d'Amandes">
                                    <input type="hidden" name="prix_produit" value="120">
                                    <input type="hidden" name="photo_produit" value="HUILE D'AMANDE  DOUCE.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/huile de coco.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Huile de coco</h5>
                                <p>Huile bio naturelle de haute qualite.</p>
                                <h4 class="text-success">110 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="10">
                                    <input type="hidden" name="nom_produit" value="Huile de coco">
                                    <input type="hidden" name="prix_produit" value="110">
                                    <input type="hidden" name="photo_produit" value="huile de coco.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION: GRAINES -->
            <div id="graines" class="products-section">
                <h2 class="mb-4">Graines Naturelles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/chia.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Graines de Chia</h5>
                                <p>Graines naturelles riches en nutriments.</p>
                                <h4 class="text-success">55 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="7">
                                    <input type="hidden" name="nom_produit" value="Graines de Chia">
                                    <input type="hidden" name="prix_produit" value="55">
                                    <input type="hidden" name="photo_produit" value="chia.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/lin.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Graines de Lin</h5>
                                <p>Graines bio naturelle de haute qualite.</p>
                                <h4 class="text-success">40 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="11">
                                    <input type="hidden" name="nom_produit" value="Graines de Lin">
                                    <input type="hidden" name="prix_produit" value="40">
                                    <input type="hidden" name="photo_produit" value="lin.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/avoine.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Avoine</h5>
                                <p>Avoine naturelle riche et artisanale.</p>
                                <h4 class="text-success">35 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="12">
                                    <input type="hidden" name="nom_produit" value="Avoine">
                                    <input type="hidden" name="prix_produit" value="35">
                                    <input type="hidden" name="photo_produit" value="avoine.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION: EPICES -->
            <div id="epices" class="products-section">
                <h2 class="mb-4">Epices Naturelles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/comin.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Cumin</h5>
                                <p>Epice naturelle marocaine de haute qualite.</p>
                                <h4 class="text-success">35 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="8">
                                    <input type="hidden" name="nom_produit" value="Cumin">
                                    <input type="hidden" name="prix_produit" value="35">
                                    <input type="hidden" name="photo_produit" value="comin.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/corcuma.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Curcuma</h5>
                                <p>Curcuma bio naturel et artisanal.</p>
                                <h4 class="text-success">45 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="13">
                                    <input type="hidden" name="nom_produit" value="Curcuma">
                                    <input type="hidden" name="prix_produit" value="45">
                                    <input type="hidden" name="photo_produit" value="corcuma.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/safran.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Safran</h5>
                                <p>Safran marocain naturel premium.</p>
                                <h4 class="text-success">150 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="14">
                                    <input type="hidden" name="nom_produit" value="Safran">
                                    <input type="hidden" name="prix_produit" value="150">
                                    <input type="hidden" name="photo_produit" value="safran.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION: HERBES -->
            <div id="herbes" class="products-section">
                <h2 class="mb-4">Herbes Naturelles</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/menthe.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Menthe</h5>
                                <p>Menthe naturelle fraeche et bio.</p>
                                <h4 class="text-success">10 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="15">
                                    <input type="hidden" name="nom_produit" value="Menthe">
                                    <input type="hidden" name="prix_produit" value="10">
                                    <input type="hidden" name="photo_produit" value="menthe.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/thyme.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Thym</h5>
                                <p>Thym naturel marocain de qualite.</p>
                                <h4 class="text-success">30 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="16">
                                    <input type="hidden" name="nom_produit" value="Thym">
                                    <input type="hidden" name="prix_produit" value="30">
                                    <input type="hidden" name="photo_produit" value="thyme.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="product-card">
                            <button class="btn-favoris"><i class="fa-regular fa-heart"></i></button>
                            <img src="imagines/verveine.jpg" class="product-img">
                            <div class="product-details">
                                <h5>Verveine</h5>
                                <p>Verveine bio naturelle et artisanale.</p>
                                <h4 class="text-success">25 DH</h4>
                                <form class="cart-form">
                                    <input type="hidden" name="id_produit" value="17">
                                    <input type="hidden" name="nom_produit" value="Verveine">
                                    <input type="hidden" name="prix_produit" value="25">
                                    <input type="hidden" name="photo_produit" value="verveine.jpg">
                                    <button type="submit" class="btn btn-green w-100">Ajouter au panier</button>
                                </form>
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
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('ajoute_panier.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const badge = document.getElementById('cart-badge');
                const total = document.getElementById('cart-total');
                if (badge) {
                    badge.innerText = data.total_quantite;
                    badge.classList.remove('d-none');
                }
                if (total) total.innerText = data.total_prix + ' DH';
                alert('Produit ajouté au panier.');
            } else {
                alert(data.message || "Erreur lors de l'ajout au panier.");
            }
        })
        .catch(() => alert('Erreur réseau.'));
    });
});
</script>

</body>
</html>