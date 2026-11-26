<?php
session_start();

$q = trim($_GET['q'] ?? '');
$staticResults = [
    ['type' => 'Produit', 'title' => "Huile d'Argan", 'description' => 'Huile naturelle pour peau et cheveux.', 'url' => 'produits.php'],
    ['type' => 'Produit', 'title' => 'Safran Marocain', 'description' => 'Epice naturelle premium.', 'url' => 'produits.php'],
    ['type' => 'Boutique', 'title' => 'Bio Nature Tetouan', 'description' => 'Produits bio et naturels.', 'url' => 'bio-nature.php'],
    ['type' => 'Boutique', 'title' => 'Cosmetiques Atlas', 'description' => 'Cosmetiques naturels marocains.', 'url' => 'cosmetiques-atlas.php'],
    ['type' => 'Boutique', 'title' => 'Artisanat Casa', 'description' => 'Produits artisanaux faits main.', 'url' => 'artisanat-casa.php'],
];

$results = [];
foreach ($staticResults as $item) {
    if ($q === '' || stripos($item['title'] . ' ' . $item['description'], $q) !== false) {
        $results[] = $item;
    }
}

try {
    if ($q !== '') {
        $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $c->prepare("SELECT nom, description FROM produit WHERE nom LIKE ? OR description LIKE ? ORDER BY date_ajout DESC LIMIT 20");
        $like = '%' . $q . '%';
        $stmt->execute([$like, $like]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $results[] = [
                'type' => 'Produit producteur',
                'title' => $row['nom'],
                'description' => $row['description'] ?: 'Produit local disponible sur GreenMarket.',
                'url' => 'produits.php'
            ];
        }
    }
} catch (PDOException $e) {
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include("header.php"); ?>
<main class="gm-page">
    <div class="container">
        <section class="gm-hero-band">
            <h1>Recherche</h1>
            <p>Resultats pour: <strong><?php echo htmlspecialchars($q ?: 'tous les contenus'); ?></strong></p>
        </section>

        <div class="row g-3">
            <?php if (empty($results)): ?>
                <div class="col-12">
                    <div class="gm-panel rounded-4 p-4">Aucun resultat trouve.</div>
                </div>
            <?php endif; ?>

            <?php foreach ($results as $result): ?>
                <div class="col-md-6">
                    <a class="gm-panel d-block rounded-4 p-4 text-decoration-none text-dark h-100" href="<?php echo htmlspecialchars($result['url']); ?>">
                        <span class="badge text-bg-success mb-2"><?php echo htmlspecialchars($result['type']); ?></span>
                        <h3 class="h5 fw-bold"><?php echo htmlspecialchars($result['title']); ?></h3>
                        <p class="mb-0 text-muted"><?php echo htmlspecialchars($result['description']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="main.js"></script>
</body>
</html>
