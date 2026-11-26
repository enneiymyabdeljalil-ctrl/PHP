<?php
require_once __DIR__ . '/app.php';
require_role(['producteur', 'administrateur', 'admin']);

try {
    $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
    $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $categories = $c->query("SELECT id, nom_categorie FROM categorie ORDER BY nom_categorie")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
require_once __DIR__ . '/app.php';
require_role(['producteur', 'administrateur', 'admin']);

try {
    $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
    $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $categories = $c->query("SELECT id, nom_categorie FROM categorie ORDER BY nom_categorie")->fetchAll(PDO::FETCH_ASSOC);
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
        $statut = in_array($_SESSION['roleu'] ?? '', ['administrateur', 'admin'], true) ? 'publie' : 'en_attente';
        $stmt = $c->prepare("INSERT INTO produit (id_user, id_categorie, nom, description, prix, stock, photo, statut_publication) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['idu'], $idCategorie, $nom, $description, $prix, $stock, $photo, $statut]);
        header("Location: " . (in_array($_SESSION['roleu'], ['administrateur', 'admin'], true) ? "dasha.php" : "dashp.php"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#FAF1E0; font-family:'Times New Roman', Times, serif;">
<div class="container py-5">
    <div class="bg-white rounded-4 shadow-sm p-4 mx-auto" style="max-width:720px;">
        <h1 class="mb-4" style="color:#69561F;">Ajouter un produit</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input class="form-control" name="nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>">
                <?php if (isset($err['nom'])): ?><div class="text-danger small"><?php echo $err['nom']; ?></div><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Categorie</label>
                <select class="form-select" name="id_categorie">
                    <option value="">Sans categorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nom_categorie']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prix</label>
                    <input class="form-control" name="prix" type="number" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['prix'] ?? ''); ?>">
                    <?php if (isset($err['prix'])): ?><div class="text-danger small"><?php echo $err['prix']; ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input class="form-control" name="stock" type="number" min="0" value="<?php echo htmlspecialchars($_POST['stock'] ?? ''); ?>">
                    <?php if (isset($err['stock'])): ?><div class="text-danger small"><?php echo $err['stock']; ?></div><?php endif; ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Photo</label>
                <input class="form-control" name="photo" placeholder="exemple.jpg" value="<?php echo htmlspecialchars($photo ?? ''); ?>">
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
        $statut = in_array($_SESSION['roleu'] ?? '', ['administrateur', 'admin'], true) ? 'publie' : 'en_attente';
        $stmt = $c->prepare("INSERT INTO produit (id_user, id_categorie, nom, description, prix, stock, photo, statut_publication) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['idu'], $idCategorie, $nom, $description, $prix, $stock, $photo, $statut]);
        header("Location: " . (in_array($_SESSION['roleu'], ['administrateur', 'admin'], true) ? "dasha.php" : "dashp.php"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#FAF1E0; font-family:'Times New Roman', Times, serif;">
<div class="container py-5">
    <div class="bg-white rounded-4 shadow-sm p-4 mx-auto" style="max-width:720px;">
        <h1 class="mb-4" style="color:#69561F;">Ajouter un produit</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input class="form-control" name="nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>">
                <?php if (isset($err['nom'])): ?><div class="text-danger small"><?php echo $err['nom']; ?></div><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Categorie</label>
                <select class="form-select" name="id_categorie">
                    <option value="">Sans categorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nom_categorie']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prix</label>
                    <input class="form-control" name="prix" type="number" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['prix'] ?? ''); ?>">
                    <?php if (isset($err['prix'])): ?><div class="text-danger small"><?php echo $err['prix']; ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input class="form-control" name="stock" type="number" min="0" value="<?php echo htmlspecialchars($_POST['stock'] ?? ''); ?>">
                    <?php if (isset($err['stock'])): ?><div class="text-danger small"><?php echo $err['stock']; ?></div><?php endif; ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Photo</label>
                <input class="form-control" name="photo" placeholder="exemple.jpg" value="<?php echo htmlspecialchars($photo ?? ''); ?>">
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
