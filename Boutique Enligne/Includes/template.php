<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <title><?= $page_title ?></title>
        <meta charset="utf-8" />
        <link href="Css/style.css" rel="stylesheet" /> 
    </head>
        
    <body>
        <div class="container">
            <?php
                include "includes/header.php"
            ?>

            <nav>
                <?php
                include "includes/menu.php"
            ?>
            </nav>

            <main>
                <?= $content ?>
            </main>

            
                <?php
                include "includes/Footer.php"
                ?>
        </div>
    </body>
</html>