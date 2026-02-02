<?php
require_once "Fonction.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connection.php");
    exit;
}

$mysqli = GetMySQLiInstance();
$commande = $_SESSION['commande'] ?? [];

ob_start();
?>

<p class="title">Ma commande</p>

<?php if (empty($commande)): ?>

    <p style="width:min(980px,92%);margin:0 auto;color:#9fb0c8;">
        Aucun produit dans votre commande.
    </p>

<?php else: ?>

<section class="products">

<?php
$ids = array_keys($commande);
$placeholders = implode(",", array_fill(0, count($ids), "?"));
$types = str_repeat("i", count($ids));

$sql = "SELECT id, nom, prix, image FROM produits WHERE id IN ($placeholders)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$res = $stmt->get_result();

$total = 0;

while ($p = $res->fetch_assoc()):
    $id = (int)$p['id'];
    $qty = (int)$commande[$id];
    $line = $qty * (float)$p['prix'];
    $total += $line;
?>

  <div class="product-card">
      <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
      <p class="product-name"><?= htmlspecialchars($p['nom']) ?></p>
      <p style="margin:0;">Quantité : <?= $qty ?></p>
      <h3 class="product-price">
          Sous-total : <?= number_format($line, 2) ?>$
      </h3>

      <form action="commande-remove.php" method="post">
          <input type="hidden" name="id" value="<?= $id ?>">
          <button type="submit">Supprimer</button>
      </form>
  </div>

<?php endwhile; ?>

</section>

<p style="width:min(980px,92%);margin:20px auto;font-weight:900;">
    Total : <?= number_format($total, 2) ?>$
</p>

<form action="valider-commande.php" method="post"  style="width:min(980px,92%);margin:0 auto;">
    <button type="submit">Valider la commande</button>
</form>


<?php endif; ?>

<?php
$content = ob_get_clean();
require('includes/template.php');
