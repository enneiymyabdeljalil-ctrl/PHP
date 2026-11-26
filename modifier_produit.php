<?php
require_once __DIR__ . '/app.php';
require_role(['producteur', 'administrateur', 'admin']);

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: dashp.php");
    exit;
}

try {
    $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
    $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $categories = $c->query("SELECT id, nom_categorie FROM categorie ORDER BY nom_categorie")->fetchAll(PDO::FETCH_ASSOC);
    if (in_array($_SESSION['roleu'], ['administrateur', 'admin'], true)) {
        $stmt = $c->prepare("SELECT * FROM produit WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        $stmt = $c->prepare("SELECT * FROM produit WHERE id = ? AND id_user = ?");
        $stmt->execute([$id, $_SESSION['idu']]);
    }
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$produit) {
        header("Location: dashp.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$err = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = filter_var($_POST['prix'] ?? null, FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
    $photo = trim($_POST['photo'] ?? '');
    $idCategorie = $_POST['id_categorie'] !== '' ? (int) $_POST['id_categorie'] : null;

    if ($nom === '') $err['nom'] = 'Veuillez entrer le nom du produit';
    if ($prix === false || $prix < 0) $err['prix'] = 'Veuillez entrer un prix valide';
    if ($stock === false || $stock < 0) $err['stock'] = 'Veuillez entrer un stock valide';

    if (!empty($_FILES['photo_file']['name']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $err['photo'] = 'Format image invalide';
        } else {
            $photo = uniqid('produit_', true) . '.' . $ext;
            move_uploaded_file($_FILES['photo_file']['tmp_name'], __DIR__ . '/imagines/' . $photo);
        }
    }

    if (empty($err)) {
        if (in_array($_SESSION['roleu'], ['administrateur', 'admin'], true)) {
            $stmt = $c->prepare("UPDATE produit SET id_categorie = ?, nom = ?, description = ?, prix = ?, stock = ?, photo = ? WHERE id = ?");
            $stmt->execute([$idCategorie, $nom, $description, $prix, $stock, $photo, $id]);
        } else {
            $stmt = $c->prepare("UPDATE produit SET id_categorie = ?, nom = ?, description = ?, prix = ?, stock = ?, photo = ? WHERE id = ? AND id_user = ?");
            $stmt->execute([$idCategorie, $nom, $description, $prix, $stock, $photo, $id, $_SESSION['idu']]);
        }
        header("Location: " . (in_array($_SESSION['roleu'], ['administrateur', 'admin'], true) ? "dasha.php" : "dashp.php"));
        exit;
    }
} else {
    $nom = $produit['nom'];
    $description = $produit['description'];
    $prix = $produit['prix'];
    $stock = $produit['stock'];
    $photo = $produit['photo'];
    $idCategorie = $produit['id_categorie'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#FAF1E0; font-family:'Times New Roman', Times, serif;">
<div class="container py-5">
    <div class="bg-white rounded-4 shadow-sm p-4 mx-auto" style="max-width:720px;">
        <h1 class="mb-4" style="color:#69561F;">Modifier un produit</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input class="form-control" name="nom" value="<?php echo htmlspecialchars($nom); ?>">
                <?php if (isset($err['nom'])): ?><div class="text-danger small"><?php echo $err['nom']; ?></div><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Categorie</label>
                <select class="form-select" name="id_categorie">
                    <option value="">Sans categorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ((int) $idCategorie === (int) $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prix</label>
                    <input class="form-control" name="prix" type="number" step="0.01" min="0" value="<?php echo htmlspecialchars($prix); ?>">
                    <?php if (isset($err['prix'])): ?><div class="text-danger small"><?php echo $err['prix']; ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input class="form-control" name="stock" type="number" min="0" value="<?php echo htmlspecialchars($stock); ?>">
                    <?php if (isset($err['stock'])): ?><div class="text-danger small"><?php echo $err['stock']; ?></div><?php endif; ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Photo</label>
                <input class="form-control" name="photo" value="<?php echo htmlspecialchars($photo); ?>">
                <input class="form-control mt-2" type="file" name="photo_file" accept="image/*">
                <?php if (isset($err['photo'])): ?><div class="text-danger small"><?php echo $err['photo']; ?></div><?php endif; ?>
            </div>
            <button class="btn btn-dark">Enregistrer</button>
            <a href="dashp.php" class="btn btn-outline-secondary">Annuler</a>
        </form>
    </div>
</div>
</body>
</html>