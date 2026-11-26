<?php
require_once __DIR__ . '/app.php';

try {
    $c = db();
} catch (PDOException $e) {
    die("Erreur de connexion" . $e->getMessage());
}
?>
