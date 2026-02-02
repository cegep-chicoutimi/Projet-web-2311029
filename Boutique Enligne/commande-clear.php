<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: connection.php");
  exit;
}

unset($_SESSION['commande']);

header("Location: commande.php");
exit;
