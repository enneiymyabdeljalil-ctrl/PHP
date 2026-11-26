<?php
session_start();
$isAdmin = in_array($_SESSION['roleu'] ?? '', ['administrateur', 'admin']);
$isProducteur = ($_SESSION['roleu'] ?? '') === 'producteur';
if (!$isAdmin && !$isProducteur) {
    header("Location: authentification.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($isAdmin) {
            $req = $c->prepare("DELETE FROM produit WHERE id = ?");
            $req->execute([$id]);
        } else {
            $req = $c->prepare("DELETE FROM produit WHERE id = ? AND id_user = ?");
            $req->execute([$id, $_SESSION['idu']]);
        }

        header("Location: " . ($isAdmin ? "dasha.php" : "dashp.php"));
        exit;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    header("Location: dashp.php");
    exit;
}
?>
