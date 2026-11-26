<?php
require_once __DIR__ . '/app.php';

try {
    $c = db();
    $req = $c->prepare(
        "SELECT id, nom, description, prix, photo
         FROM produit
         WHERE statut_publication = 'publie'
         ORDER BY date_ajout DESC, id DESC
         LIMIT 6"
    );
    $req->execute();
    $produitsDynamiques = $req->fetchAll();

    if (empty($produitsDynamiques)) {
        $req2 = $c->prepare("SELECT id, nom, description, prix, photo FROM produit ORDER BY date_ajout DESC, id DESC LIMIT 6");
        $req2->execute();
        $produitsDynamiques = $req2->fetchAll();
    }
} catch (PDOException $e) {
    $produitsDynamiques = [];
}

$favIds = is_logged_in() ? favoris_ids((int) $_SESSION['idu']) : [];
$idOffsetDynamique = 1000;

$staticProducts = [
    ['id' => 1, 'nom' => 'Fraises',             'prix' => 20,  'img' => 'FRESAS.jpg',       'badge' => 'Frais'],
    ['id' => 2, 'nom' => "Huile d'Olive",        'prix' => 95,  'img' => 'Aceite Vegetal.jpg','badge' => 'Artisanal'],
    ['id' => 3, 'nom' => 'Pain Traditionnel',    'prix' => 5,   'img' => 'PAN.jpg',           'badge' => 'Fait Maison'],
    ['id' => 4, 'nom' => 'Bougies Artisanales',  'prix' => 65,  'img' => 'bougie.jpg',        'badge' => 'Artisanal'],
    ['id' => 5, 'nom' => 'Savon Naturel',        'prix' => 60,  'img' => 'savon.jpg',         'badge' => 'Naturel'],
    ['id' => 6, 'nom' => 'Sac Artisanal',        'prix' => 50,  'img' => 'sacz.jpg',          'badge' => 'Artisanal'],
];
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
body { background-color: #FAF7F0; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; font-size: 16px; color: #69561F; }
.hero { background-image: linear-gradient(120deg, rgba(250,241,224,0.92), rgba(250,241,224,0.75)), url('imagines/hero.png'); background-size: cover; background-position: center; background-repeat: no-repeat; color: #69561F; padding: 130px 0 110px; border-radius: 0 0 70px 70px; box-shadow: 0 20px 50px rgba(105,86,31,0.08); position: relative; overflow: hidden; min-height: 520px; display: flex; align-items: center; }
.hero-badge { display: inline-flex; align-items: center; gap: 8px; background-color: rgba(85,107,47,0.12); color: #556B2F; font-weight: 700; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; padding: 8px 18px; border-radius: 30px; margin-bottom: 24px; }
.hero-content { max-width: 620px; text-align: left; }
.hero h1 { font-family: 'Playfair Display', Georgia, serif; font-size: 56px; font-weight: 700; letter-spacing: -1px; line-height: 1.1; margin-bottom: 22px; color: #4A3E1B; }
.hero p.lead { font-size: 19px; color: #6B5A26; line-height: 1.7; margin-bottom: 38px; }
.hero-cta-group { display: flex; gap: 16px; flex-wrap: wrap; }
.hero::before { content: '🌿'; position: absolute; font-size: 26px; top: -10%; left: 8%; animation: floatLeaf 9s linear infinite; }
.hero::after { content: '🌱'; position: absolute; font-size: 22px; bottom: 12%; right: 12%; animation: floatLeaf 11s linear infinite reverse; }
@keyframes floatLeaf { 0% { transform: translateY(0) rotate(0deg) scale(0.8); opacity: 0; } 10% { opacity: 0.45; } 90% { opacity: 0.45; } 100% { transform: translateY(260px) rotate(360deg) scale(1.2); opacity: 0; } }
.btn-hero { background-color: #69561F; color: #FAF1E0 !important; font-weight: 700; padding: 15px 36px; border-radius: 14px; border: 2px solid #69561F; box-shadow: 0 10px 28px rgba(105,86,31,0.25); transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
.btn-hero:hover { background-color: #534415; border-color: #534415; color: #FAF1E0 !important; transform: translateY(-3px); box-shadow: 0 14px 32px rgba(105,86,31,0.32); }
.btn-hero-outline { background-color: transparent; color: #69561F !important; font-weight: 700; padding: 15px 36px; border-radius: 14px; border: 2px solid rgba(105,86,31,0.35); transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
.btn-hero-outline:hover { border-color: #69561F; background-color: rgba(105,86,31,0.06); transform: translateY(-3px); }
.trust-strip { background-color: #ffffff; border-radius: 22px; box-shadow: 0 12px 35px rgba(105,86,31,0.06); padding: 28px 10px; margin-top: -45px; position: relative; z-index: 5; }
.trust-item { display: flex; align-items: center; justify-content: center; gap: 12px; font-weight: 600; color: #4A3E1B; font-size: 15px; }
.trust-item i { color: #556B2F; font-size: 20px; }
.section-eyebrow { text-align: center; color: #8C9A4E; font-weight: 700; font-size: 13px; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 10px; }
.section-title { font-family: 'Playfair Display', Georgia, serif; font-size: 38px; font-weight: 700; color: #4A3E1B; text-align: center; margin-bottom: 14px; }
.section-subtitle { text-align: center; color: #7A725D; max-width: 560px; margin: 0 auto 50px; font-size: 16px; line-height: 1.6; }
.section-divider { display: flex; align-items: center; justify-content: center; gap: 14px; margin: 70px 0 10px; }
.section-divider span { height: 1px; width: 60px; background-color: rgba(105,86,31,0.2); }
.section-divider i { color: #8C9A4E; font-size: 14px; }
.card { background-color: #ffffff; border: none; border-radius: 22px; box-shadow: 0 8px 24px rgba(105,86,31,0.06); transition: all 0.35s ease; overflow: hidden; height: 100%; display: flex; flex-direction: column; position: relative; }
.card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(105,86,31,0.14); }
.product-img-wrap { position: relative; background-color: #fbf9f4; }
.product-img { width: 100%; height: 230px; object-fit: contain; padding: 18px; display: block; }
.product-badge { position: absolute; top: 14px; left: 14px; background-color: #556B2F; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; padding: 6px 14px; border-radius: 30px; box-shadow: 0 4px 12px rgba(85,107,47,0.3); }
.card-body { padding: 22px 22px 24px; text-align: center; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; }
.card-title { font-family: 'Playfair Display', Georgia, serif; color: #4A3E1B; font-weight: 700; font-size: 21px; margin-bottom: 8px; }
.card-text { color: #8A7F63; font-size: 14.5px; line-height: 1.5; margin-bottom: 16px; min-height: 42px; }
.product-price { color: #556B2F; font-weight: 800; font-size: 23px; margin-bottom: 16px; }
.btn-favoris { position: absolute; top: 14px; right: 14px; background-color: rgba(255,255,255,0.85); border: none; width: 38px; height: 38px; border-radius: 50px; display: flex; align-items: center; justify-content: center; color: #69561F; font-size: 16px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: all 0.3s ease; z-index: 10; text-decoration: none; }
.btn-favoris:hover { background-color: #ffffff; transform: scale(1.1); color: #dc3545; }
.btn-favoris:active { transform: scale(0.9); }
.btn-favoris.active { color: #dc3545; background-color: #ffffff; }
.btn-green, a.btn-green { background-color: #69561F !important; color: #FAF1E0 !important; border: none !important; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 14.5px; letter-spacing: 0.2px; transition: all 0.25s ease; display: block; width: 100%; text-decoration: none; }
.btn-green:hover, a.btn-green:hover { background-color: #534415 !important; color: #FAF1E0 !important; transform: translateY(-1px); }
footer { background: #69561F; color: #FAF1E0; text-align: center; padding: 40px; margin-top: 80px; }
footer p { margin-bottom: 5px; opacity: 0.8; }
@keyframes flyToCart { 0% { opacity: 1; transform: translate(0, 0) scale(1); } 100% { opacity: 0.1; transform: translate(var(--target-x), var(--target-y)) scale(0.2); } }
.flying-item { position: fixed; z-index: 9999; width: 50px; height: 50px; background-color: #69561F; border-radius: 50px; pointer-events: none; }
@keyframes cartPulse { 0% { transform: scale(1); } 50% { transform: scale(1.3); color: #dc3545; } 100% { transform: scale(1); } }
.pulse-animation { animation: cartPulse 0.4s ease-in-out; }
    </style>
</head>
<body>
<?php
$afficher_recherche = true;
include("header.php");
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge"><i class="fa-solid fa-leaf"></i> 100% Local &amp; Durable</span>
            <h1>Bienvenue sur GreenMarket</h1>
            <p class="lead">Achetez des produits locaux, bio et artisanaux directement chez les producteurs.</p>
            <div class="hero-cta-group">
                <a href="produits.php" class="btn-hero"><i class="fa-solid fa-basket-shopping"></i> Voir les produits</a>
                <a href="#produits" class="btn-hero-outline">Découvrir <i class="fa-solid fa-arrow-down"></i></a>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="trust-strip">
        <div class="row g-3">
            <div class="col-6 col-md-3"><div class="trust-item"><i class="fa-solid fa-seedling"></i> Produits Bio</div></div>
            <div class="col-6 col-md-3"><div class="trust-item"><i class="fa-solid fa-truck-fast"></i> Livraison Rapide</div></div>
            <div class="col-6 col-md-3"><div class="trust-item"><i class="fa-solid fa-handshake"></i> Producteurs Locaux</div></div>
            <div class="col-6 col-md-3"><div class="trust-item"><i class="fa-solid fa-shield-heart"></i> Qualité Garantie</div></div>
        </div>
    </div>
</div>

<section id="produits" class="py-5 mt-4">
    <div class="container">
        <p class="section-eyebrow">Sélection de la maison</p>
        <h2 class="section-title">Nos Produits Populaires</h2>
        <p class="section-subtitle">Une sélection soignée de produits frais, artisanaux et écologiques, choisis pour leur qualité et leur authenticité.</p>

        <div class="row g-4">
            <?php foreach ($staticProducts as $sp): ?>
            <?php $isFav = in_array($sp['id'], $favIds); ?>
            <div class="col-md-4">
                <div class="card">
                    <?php if (is_logged_in()): ?>
                        <a href="add_favoris.php?id=<?php echo $sp['id']; ?>"
                           class="btn-favoris<?php echo $isFav ? ' active' : ''; ?>"
                           data-id="<?php echo $sp['id']; ?>">
                            <i class="<?php echo $isFav ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                        </a>
                    <?php else: ?>
                        <a href="authentification.php" class="btn-favoris"><i class="fa-regular fa-heart"></i></a>
                    <?php endif; ?>
                    <div class="product-img-wrap">
                        <span class="product-badge"><?php echo h($sp['badge']); ?></span>
                        <img src="imagines/<?php echo h($sp['img']); ?>" class="product-img" alt="<?php echo h($sp['nom']); ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo h($sp['nom']); ?></h5>
                        <p class="product-price"><?php echo $sp['prix']; ?> DH</p>
                        <?php if (is_logged_in()): ?>
                            <button type="button" class="btn btn-green w-100 btn-ajouter-panier"
                                    data-id="<?php echo $sp['id']; ?>"
                                    data-nom="<?php echo h($sp['nom']); ?>"
                                    data-prix="<?php echo $sp['prix']; ?>">
                                Ajouter au panier
                            </button>
                        <?php else: ?>
                            <a href="authentification.php" class="btn btn-green w-100">Ajouter au panier</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($produitsDynamiques)): ?>
<div class="container">
    <div class="section-divider"><span></span><i class="fa-solid fa-leaf"></i><span></span></div>
</div>
<section id="nouveautes" class="py-5">
    <div class="container">
        <p class="section-eyebrow">Tout juste arrivés</p>
        <h2 class="section-title">Nouveautés de nos Producteurs</h2>
        <p class="section-subtitle">Découvrez les derniers produits ajoutés par notre communauté de producteurs locaux.</p>
        <div class="row g-4">
            <?php foreach ($produitsDynamiques as $p): ?>
            <?php
                $idDb     = (int) $p['id'];
                $idPanier = $idOffsetDynamique + $idDb;
                $isFav    = in_array($idDb, $favIds);
                $nom      = h($p['nom']);
                $desc     = h($p['description'] ?: 'Produit proposé par nos producteurs partenaires.');
                $photo    = h($p['photo'] ?: 'imagines/logoo.jpeg');
                $prix     = number_format((float) $p['prix'], 2, '.', '');
            ?>
            <div class="col-md-4">
                <div class="card">
                    <?php if (is_logged_in()): ?>
                        <a href="add_favoris.php?id=<?php echo $idDb; ?>"
                           class="btn-favoris<?php echo $isFav ? ' active' : ''; ?>"
                           data-id="<?php echo $idDb; ?>">
                            <i class="<?php echo $isFav ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                        </a>
                    <?php else: ?>
                        <a href="authentification.php" class="btn-favoris"><i class="fa-regular fa-heart"></i></a>
                    <?php endif; ?>
                    <div class="product-img-wrap">
                        <span class="product-badge">Nouveau</span>
                        <img src="<?php echo $photo; ?>" class="product-img" alt="<?php echo $nom; ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $nom; ?></h5>
                        <p class="card-text"><?php echo $desc; ?></p>
                        <p class="product-price"><?php echo $prix; ?> DH</p>
                        <?php if (is_logged_in()): ?>
                            <button type="button" class="btn btn-green w-100 btn-ajouter-panier"
                                    data-id="<?php echo $idPanier; ?>"
                                    data-nom="<?php echo $nom; ?>"
                                    data-prix="<?php echo $prix; ?>">
                                Ajouter au panier
                            </button>
                        <?php else: ?>
                            <a href="authentification.php" class="btn btn-green w-100">Ajouter au panier</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<footer>
    <h4 class="fw-bold">GreenMarket</h4>
    <p>Marketplace de produits bio et du terroir marocain</p>
    <p class="small opacity-50 mt-3">©️ 2026 Tous droits réservés</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-ajouter-panier').forEach(button => {
    button.addEventListener('click', function() {
        const id   = this.getAttribute('data-id');
        const nom  = this.getAttribute('data-nom');
        const prix = parseFloat(this.getAttribute('data-prix'));

        const formData = new FormData();
        formData.append('id_produit',   id);
        formData.append('nom_produit',  nom);
        formData.append('prix_produit', prix);

        fetch('ajoute_panier.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                const cartIcon  = document.getElementById('cart-icon');
                const cartBadge = document.getElementById('cart-badge');
                const cartTotal = document.getElementById('cart-total');
                if (!cartIcon) return;

                const buttonRect = this.getBoundingClientRect();
                const cartRect   = cartIcon.getBoundingClientRect();
                const flyer = document.createElement('div');
                flyer.classList.add('flying-item');
                flyer.style.left = `${buttonRect.left + buttonRect.width / 2 - 25}px`;
                flyer.style.top  = `${buttonRect.top + buttonRect.height / 2 - 25}px`;
                flyer.style.setProperty('--target-x', `${cartRect.left - buttonRect.left}px`);
                flyer.style.setProperty('--target-y', `${cartRect.top  - buttonRect.top}px`);
                flyer.style.animation = 'flyToCart 0.8s forwards';
                document.body.appendChild(flyer);
                flyer.addEventListener('animationend', () => {
                    flyer.remove();
                    cartIcon.classList.add('pulse-animation');
                    setTimeout(() => cartIcon.classList.remove('pulse-animation'), 400);
                    cartBadge.innerText = data.total_quantite;
                    cartBadge.classList.remove('d-none');
                    if (cartTotal) cartTotal.innerText = data.total_prix + ' DH';
                });
            }
        })
        .catch(err => console.error('Erreur:', err));
    });
});

document.querySelectorAll('.btn-favoris[data-id]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const formData = new FormData();
        formData.append('id_produit', id);

        fetch('add_favoris.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const icon = this.querySelector('i');
                if (data.active) {
                    icon.className = 'fa-solid fa-heart';
                    this.classList.add('active');
                } else {
                    icon.className = 'fa-regular fa-heart';
                    this.classList.remove('active');
                }
            }
        })
        .catch(err => console.error('Erreur favoris:', err));
    });
});
</script>
<script src="main.js"></script>
</body>
</html>