<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $pdo = new PDO(
        "mysql:host=localhost;port=3306;dbname=greenmarket;charset=utf8mb4",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool
{
    return isset($_SESSION['idu']) && !empty($_SESSION['idu']);
}

function current_role(): string
{
    return $_SESSION['roleu'] ?? '';
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('authentification.php');
    }
}

function require_role(array $roles): void
{
    require_login();
    if (!in_array(current_role(), $roles, true)) {
        redirect('acceuil.php');
    }
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function flash_all(): array
{
    $flashes = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $flashes;
}

function active_cart_id(int $idUser): int
{
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id FROM panier WHERE id_client = ? AND statut = 'actif' LIMIT 1");
    $stmt->execute([$idUser]);
    $row = $stmt->fetch();
    if ($row) {
        return (int) $row['id'];
    }
    $stmt = $pdo->prepare("INSERT INTO panier (id_client, statut) VALUES (?, 'actif')");
    $stmt->execute([$idUser]);
    return (int) $pdo->lastInsertId();
}

function cart_summary(int $idUser): array
{
    try {
        $idPanier = active_cart_id($idUser);
        $stmt = db()->prepare(
            "SELECT COALESCE(SUM(quantite), 0) AS total_quantite,
                    COALESCE(SUM(quantite * prix_unitaire), 0) AS total_prix
             FROM ligne_panier
             WHERE id_panier = ?"
        );
        $stmt->execute([$idPanier]);
        $row = $stmt->fetch();
        return [
            'total_quantite' => (int) $row['total_quantite'],
            'total_prix'     => number_format((float) $row['total_prix'], 2, '.', ''),
        ];
    } catch (PDOException $e) {
        return ['total_quantite' => 0, 'total_prix' => '0.00'];
    }
}

function cart_items(int $idUser): array
{
    try {
        $idPanier = active_cart_id($idUser);
        $stmt = db()->prepare(
            "SELECT p.id, p.nom, p.description, p.photo,
                    lp.quantite, lp.prix_unitaire AS prix
             FROM ligne_panier lp
             JOIN produit p ON p.id = lp.id_produit
             WHERE lp.id_panier = ?"
        );
        $stmt->execute([$idPanier]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function favoris_ids(int $idUser): array
{
    try {
        $stmt = db()->prepare("SELECT id_produit FROM favoris WHERE id_utilisateur = ?");
        $stmt->execute([$idUser]);
        return array_column($stmt->fetchAll(), 'id_produit');
    } catch (PDOException $e) {
        return [];
    }
}
function redirect_dashboard($role) {
    if ($role === 'admin') {
        header("Location: dasha.php");
    } elseif ($role === 'producteur') {
        header("Location: dashp.php"); 
    } else {
        header("Location: dashc.php");
    }
    exit();
}