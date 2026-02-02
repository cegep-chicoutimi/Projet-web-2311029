<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: connection.php");
  exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0 && isset($_SESSION['commande'][$id])) {
  unset($_SESSION['commande'][$id]);
}

header("Location: commande.php");
exit;
