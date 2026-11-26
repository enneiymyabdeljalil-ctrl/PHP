<?php
require_once __DIR__ . '/app.php';
require_login();

$total_general = 0;
$produits = cart_items((int) $_SESSION['idu']);
foreach ($produits as $p) {
    $total_general += (float) $p['prix'] * (int) $p['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - GreenMarket</title>

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
            background-color: #69561F;
            padding: 12px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .navbar-brand, .nav-link {
            color: #FAF1E0 !important;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .nav-link:hover { opacity: 0.8; }
        h2 {
            color: #69561F;
            font-weight: bold;
            font-size: 32px;
        }
        .cart-card {
            border: 1px solid rgba(105, 86, 31, 0.05);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            transition: transform 0.2s ease;
        }
        .cart-card:hover { transform: translateY(-2px); }
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .product-title {
            color: #3A321A;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .product-desc {
            color: #777;
            font-size: 14px;
            max-width: 400px;
        }
        .price {
            color: #69561F;
            font-weight: bold;
            font-size: 22px;
        }
        .btn-delete {
            background-color: #FAF1E0;
            color: #A04040;
            border: none;
            padding: 10px 14px;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .btn-delete:hover {
            background-color: #FADBD8;
            color: #78281F;
        }
        .qty-box {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qty-btn {
            background-color: #FAF1E0;
            border: 1px solid #69561F;
            color: #69561F;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qty-btn:hover {
            background-color: #69561F;
            color: #FAF1E0;
        }
        .qty-value {
            font-weight: bold;
            font-size: 16px;
            min-width: 24px;
            text-align: center;
        }
        .total-box {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.04);
            border: 1px solid rgba(105, 86, 31, 0.05);
        }
        .total-title { color: #3A321A; font-weight: bold; }
        .total-price {
            color: #556B2F;
            font-weight: 800;
            font-size: 28px;
        }
        .btn-green {
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(105, 86, 31, 0.15);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #69561F;
             color: white;
             }
        .btn-green:hover {
            background-color: #69561F;
            color: #FAF1E0;
            transform: translateY(-2px);
        }
        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        html, body {
    background: #fff;
}
    </style>
</head>
<body>
<?php include("header.php")?>
<!-- Toast -->
<div class="toast-container">
    <div id="toastMsg" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastText">Action effectuée</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>



<div class="container py-5">
    <h2 class="mb-5 text-center">Mon Panier <i class="fa-solid fa-basket-shopping ms-2"></i></h2>

    <div class="row justify-content-center">
        <div class="col-lg-8" id="panier-container">

            <?php if (empty($produits)): ?>
                <div class="text-center py-5 bg-white rounded-4 shadow-sm mb-4">
                    <i class="fa-regular fa-folder-open fs-1 text-muted mb-3 d-block"></i>
                    <p class="fs-5 text-muted">Votre panier est vide pour le moment.</p>
                    <a href="boutique.php" class="btn btn-dark px-4 py-2 fs-6">Retourner à la boutique</a>
                </div>
            <?php else: ?>

                <?php foreach ($produits as $p): ?>
                <?php $id_prod = (int) $p['id']; ?>
                <div class="cart-card d-flex align-items-center justify-content-between flex-wrap gap-3" id="item-<?php echo $id_prod; ?>" data-prix="<?php echo htmlspecialchars($p['prix']); ?>">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <!-- FIX: photo avec fallback si vide -->
                        <img src="imagines/<?php echo htmlspecialchars($p['photo'] ?? 'logoo.jpeg'); ?>" 
                             class="product-img me-2"
                             onerror="this.src='imagines/logoo.jpeg'">
                        <div>
                            <h5 class="product-title"><?php echo htmlspecialchars($p['nom']); ?></h5>
                            <p class="product-desc mb-0"><?php echo htmlspecialchars($p['description'] ?? ''); ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 ms-auto flex-wrap">
                        <!-- FIX: boutons modifier quantité -->
                        <div class="qty-box">
                            <button class="qty-btn" onclick="modifierQte('<?php echo $id_prod; ?>', -1)">−</button>
                            <span class="qty-value" id="qty-<?php echo $id_prod; ?>"><?php echo $p['quantite']; ?></span>
                            <button class="qty-btn" onclick="modifierQte('<?php echo $id_prod; ?>', 1)">+</button>
                        </div>

                        <div class="price" id="prix-<?php echo $id_prod; ?>">
                            <?php echo number_format((float) $p['prix'] * (int) $p['quantite'], 2, '.', ''); ?> DH
                        </div>

                        <!-- FIX: supprimer via AJAX au lieu de supprimer_item.php -->
                        <button class="btn btn-delete" onclick="supprimerItem('<?php echo $id_prod; ?>')" title="Supprimer du panier">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="total-box mt-4" id="total-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="total-title mb-0">Total Général :</h4>
                        <h3 class="total-price mb-0" id="total-general"><?php echo number_format($total_general, 2, '.', ''); ?> DH</h3>
                    </div>
                    <a class="btn btn-green w-100 mt-2" href="paye.php">
                        <i class="fa-solid fa-credit-card me-2"></i> Passer la commande
                    </a>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ---- Afficher toast ----
function showToast(message, type = 'success') {
    const toast = document.getElementById('toastMsg');
    const toastText = document.getElementById('toastText');
    toast.classList.remove('bg-success', 'bg-danger');
    toast.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
    toastText.textContent = message;
    const bsToast = new bootstrap.Toast(toast, { delay: 2500 });
    bsToast.show();
}

// ---- Recalculer total général ----
function recalculerTotal() {
    let total = 0;
    document.querySelectorAll('.cart-card').forEach(card => {
        const id = card.id.replace('item-', '');
        const prix = parseFloat(card.dataset.prix);
        const qty = parseInt(document.getElementById('qty-' + id).textContent);
        total += prix * qty;
    });
    const el = document.getElementById('total-general');
    if (el) el.textContent = total.toFixed(2) + ' DH';
}

// ---- Modifier quantité ----
function modifierQte(id, delta) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('delta', delta);

    fetch('modifier_quantite.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.quantite <= 0) {
                // Supprimer la carte si quantité = 0
                document.getElementById('item-' + id)?.remove();
                showToast('Produit retiré du panier');
            } else {
                document.getElementById('qty-' + id).textContent = data.quantite;
                const card = document.getElementById('item-' + id);
                const prix = parseFloat(card.dataset.prix);
                document.getElementById('prix-' + id).textContent = (prix * data.quantite).toFixed(2) + ' DH';
            }
            recalculerTotal();
            updateHeaderCart(data);
            // Vider panier si plus d'articles
            if (document.querySelectorAll('.cart-card').length === 0) {
                location.reload();
            }
        } else {
            showToast('Erreur lors de la modification', 'danger');
        }
    })
    .catch(() => showToast('Erreur réseau', 'danger'));
}

// ---- Supprimer article ----
function supprimerItem(id) {
    if (!confirm('Supprimer ce produit du panier ?')) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch('supprimer_item.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('item-' + id)?.remove();
            recalculerTotal();
            updateHeaderCart(data);
            showToast('Produit supprimé du panier');
            if (document.querySelectorAll('.cart-card').length === 0) {
                location.reload();
            }
        } else {
            showToast('Erreur lors de la suppression', 'danger');
        }
    })
    .catch(() => showToast('Erreur réseau', 'danger'));
}

function updateHeaderCart(data) {
    const badge = document.getElementById('cart-badge');
    const total = document.getElementById('cart-total');
    if (badge && data.total_quantite !== undefined) {
        badge.textContent = data.total_quantite;
        badge.classList.toggle('d-none', parseInt(data.total_quantite, 10) <= 0);
    }
    if (total && data.total_prix !== undefined) {
        total.textContent = data.total_prix + ' DH';
    }
}
</script>

</body>
</html>