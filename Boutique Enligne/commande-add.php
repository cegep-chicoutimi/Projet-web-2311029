<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: connection.php");
  exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
  header("Location: index.php");
  exit;
}

if (!isset($_SESSION['commande'])) {
  $_SESSION['commande'] = []; // [idProduit => quantite]
}

$_SESSION['commande'][$id] = ($_SESSION['commande'][$id] ?? 0) + 1;

header("Location: commande.php");
exit;
