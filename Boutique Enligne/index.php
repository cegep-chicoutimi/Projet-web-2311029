<?php 
require_once "Fonction.php";
$mysqli = GetMySQLiInstance();

$result = $mysqli->query("SELECT id, nom, prix, image FROM produits ORDER BY id DESC");
if (!$result) {
    die("Erreur SQL: " . htmlspecialchars($mysqli->error));
}

?>

<?php ob_start(); ?>

<p class="title">Produits populaires</p>

<section class="products">
<?php while ($p = $result->fetch_assoc()): ?>
  <div class="product-card">
      <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
      <p class="product-name"><?= htmlspecialchars($p['nom']) ?></p>
      <h3 class="product-price"><?= number_format((float)$p['prix'], 2) ?>$</h3>
      <form action="commande-add.php" method="post">
        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
        <button type="submit">Ajouter</button>
     </form>


  </div>
<?php endwhile; ?>
</section>

<?php $content = ob_get_clean(); ?> 

<?php require('includes/template.php'); ?>
