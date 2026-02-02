<?php
require_once "Fonction.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: connection.php");
    exit;
}

$mysqli = GetMySQLiInstance();
$clientId = (int)$_SESSION['user_id'];

$stmt = $mysqli->prepare("
    SELECT id, total, created_at
    FROM commandes
    WHERE client_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$res = $stmt->get_result();

ob_start();
?>

<p class="title">
    Historique des commandes — Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?>
</p>

<?php if ($res->num_rows === 0): ?>

    <p style="width:min(980px,92%);margin:0 auto;color:#9fb0c8;">
        Aucune commande enregistrée pour le moment.
    </p>

<?php else: ?>

    <section style="width:min(980px,92%);margin:0 auto 28px;display:flex;flex-direction:column;gap:12px;">

    <?php while ($c = $res->fetch_assoc()):
        $commandeId = (int)$c['id'];
        $total = (float)$c['total'];
        $date = $c['created_at'];

        
        $stmt2 = $mysqli->prepare("
            SELECT nom_produit, prix_unitaire, quantite
            FROM commande_lignes
            WHERE commande_id = ?
            ORDER BY id ASC
        ");
        $stmt2->bind_param("i", $commandeId);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
    ?>

        <div style="border:1px solid rgba(255,255,255,.12);border-radius:16px;background:rgba(255,255,255,.04);padding:14px;">
            <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <div style="font-weight:950;">Commande #<?= $commandeId ?></div>
                <div style="color:#9fb0c8;">Date : <?= htmlspecialchars($date) ?></div>
            </div>

            <div style="margin-top:10px;">
                <ul style="margin:0;padding-left:18px;color:#eaf0ff;">
                    <?php while ($l = $res2->fetch_assoc()):
                        $nom = $l['nom_produit'];
                        $prix = (float)$l['prix_unitaire'];
                        $qty = (int)$l['quantite'];
                    ?>
                        <li>
                            <?= htmlspecialchars($nom) ?>
                            — <?= number_format($prix, 2) ?>$ × <?= $qty ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div style="margin-top:12px;font-weight:950;">
                Total : <?= number_format($total, 2) ?>$
            </div>
        </div>

    <?php
        $stmt2->close();
    endwhile; ?>

    </section>

<?php endif; ?>

<?php
$stmt->close();

$content = ob_get_clean();
require('includes/template.php');
