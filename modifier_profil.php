<?php
require_once __DIR__ . '/app.php';
require_login();

$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->execute([$_SESSION['idu']]);
$user = $stmt->fetch();
if (!$user) {
    redirect('deconnexion.php');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($nom === '') $errors[] = 'Le nom est obligatoire.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
    if ($adresse === '') $errors[] = 'Adresse obligatoire.';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ? AND id <> ?");
    $stmt->execute([$email, $_SESSION['idu']]);
    if ((int) $stmt->fetchColumn() > 0) {
        $errors[] = 'Cet email est déjà utilisé.';
    }

    $passwordSql = '';
    $params = [$nom, $prenom, $email, $adresse, $telephone];
    if ($newPassword !== '' || $currentPassword !== '' || $confirmPassword !== '') {
        if (!password_verify($currentPassword, $user['mot_de_passe']) && $currentPassword !== $user['mot_de_passe']) {
            $errors[] = 'Mot de passe actuel incorrect.';
        }
        if (strlen($newPassword) < 6) {
            $errors[] = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'La confirmation du mot de passe ne correspond pas.';
        }
        if (empty($errors)) {
            $passwordSql = ', mot_de_passe = ?';
            $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    if (empty($errors)) {
        $params[] = $_SESSION['idu'];
        $stmt = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, adresse = ?, telephone = ? $passwordSql WHERE id = ?");
        $stmt->execute($params);
        $_SESSION['nomu'] = $nom;
        flash_set('success', 'Profil mis à jour avec succès.');
        redirect('profile.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#FAF1E0; font-family:'Times New Roman', Times, serif;">
<div class="container py-5">
    <div class="bg-white rounded-4 shadow-sm p-4 mx-auto" style="max-width:760px;">
        <h1 class="mb-4" style="color:#69561F;">Modifier mon profil</h1>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?php echo h($error); ?></div>
        <?php endforeach; ?>
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="nom" value="<?php echo h($_POST['nom'] ?? $user['nom']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom</label>
                    <input class="form-control" name="prenom" value="<?php echo h($_POST['prenom'] ?? $user['prenom']); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" value="<?php echo h($_POST['email'] ?? $user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input class="form-control" name="adresse" value="<?php echo h($_POST['adresse'] ?? $user['adresse']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Téléphone</label>
                <input class="form-control" name="telephone" value="<?php echo h($_POST['telephone'] ?? $user['telephone']); ?>">
            </div>
            <h4 class="mb-3" style="color:#69561F;">Changer le mot de passe</h4>
            <div class="mb-3">
                <label class="form-label">Mot de passe actuel</label>
                <input class="form-control" type="password" name="current_password">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input class="form-control" type="password" name="new_password">
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label">Confirmer</label>
                    <input class="form-control" type="password" name="confirm_password">
                </div>
            </div>
            <button class="btn btn-dark">Enregistrer</button>
            <a href="profile.php" class="btn btn-outline-secondary">Annuler</a>
        </form>
    </div>
</div>
</body>
</html>
