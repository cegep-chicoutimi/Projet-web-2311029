<?php

$nbCommande = 0;
if (!empty($_SESSION['commande'])) {
    foreach ($_SESSION['commande'] as $q) {
        $nbCommande += (int)$q;
    }
}
?>

<div id="nav-links">

    <a href="index.php">Accueil</a>
    <a href="commande.php">Commande (<?= $nbCommande ?>)</a>
    <a href="historique.php">Historique</a>

    <?php if (isset($_SESSION['user_id'])): ?>

        <span class="user-name">
            Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?>
        </span>

        <a href="logout.php" class="logout-btn">Déconnexion</a>

    <?php else: ?>

        <a href="connection.php">Connexion</a>
        <a href="inscription.php">Inscription</a>

    <?php endif; ?>

</div>
