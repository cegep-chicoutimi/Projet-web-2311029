<?php
require_once "Fonction.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: connection.php");
    exit;
}

$commande = $_SESSION['commande'] ?? [];
if (empty($commande)) {
    die("Votre commande est vide.");
}

$mysqli = GetMySQLiInstance();
$clientId = (int)$_SESSION['user_id'];

// 1) Récupérer les produits concernés
$ids = array_keys($commande);
$placeholders = implode(",", array_fill(0, count($ids), "?"));
$types = str_repeat("i", count($ids));

$sql = "SELECT id, nom, prix FROM produits WHERE id IN ($placeholders)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$res = $stmt->get_result();

// 2) Calculer total + préparer les lignes
$total = 0;
$lignes = [];

while ($p = $res->fetch_assoc()) {
    $pid = (int)$p['id'];
    $qty = (int)($commande[$pid] ?? 0);
    if ($qty <= 0) continue;

    $prix = (float)$p['prix'];
    $total += $prix * $qty;

    $lignes[] = [
        'produit_id' => $pid,
        'nom' => $p['nom'],
        'prix' => $prix,
        'qty' => $qty
    ];
}
$stmt->close();

if (empty($lignes)) {
    die("Produits introuvables.");
}


$mysqli->begin_transaction();

try {
    // 4) Insérer la commande
    $stmt2 = $mysqli->prepare("INSERT INTO commandes (client_id, total) VALUES (?, ?)");
    $stmt2->bind_param("id", $clientId, $total);
    if (!$stmt2->execute()) {
        throw new Exception($stmt2->error);
    }
    $commandeId = (int)$stmt2->insert_id;
    $stmt2->close();

    // 5) Insérer les lignes
    $stmt3 = $mysqli->prepare("
        INSERT INTO commande_lignes (commande_id, produit_id, nom_produit, prix_unitaire, quantite)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($lignes as $it) {
        $pid = (int)$it['produit_id'];
        $nom = $it['nom'];
        $prix = (float)$it['prix'];
        $qty = (int)$it['qty'];

        $stmt3->bind_param("iisdi", $commandeId, $pid, $nom, $prix, $qty);
        if (!$stmt3->execute()) {
            throw new Exception($stmt3->error);
        }
    }
    $stmt3->close();

   
    $mysqli->commit();

   
    unset($_SESSION['commande']);

   
    header("Location: historique.php");
    exit;

} catch (Exception $e) {
    $mysqli->rollback();
    die("Erreur validation commande : " . htmlspecialchars($e->getMessage()));
}
