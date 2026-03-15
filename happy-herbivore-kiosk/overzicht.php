<?php
// dit is het overzichtsscherm
// de klant ziet hier alles wat ze hebben besteld
// ze kunnen hier nog aanpassen of verwijderen
require_once 'includes/init.php';
require_once 'includes/header.php';
require_once 'includes/menu_data.php';

// als er iets gepost is verwerk ik dat hier, dus een verwijdering of een aanpassing van het aantal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie   = $_POST['actie']   ?? '';
    $cart_id = $_POST['cart_id'] ?? '';

    if ($actie === 'verwijder' && $cart_id) {
        cart_verwijder($cart_id);
    } elseif ($actie === 'update' && $cart_id) {
        $nieuw_aantal = (int)($_POST['aantal'] ?? 1);
        cart_update_aantal($cart_id, $nieuw_aantal);
    }

    header('Location: overzicht.php');
    exit;
}

$cart        = $_SESSION['cart'] ?? [];
$totaal      = cart_totaal();
$heeft_items = !empty($cart);

// cat_icoon kan ik hier al gebruiken omdat header.php al geladen is
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore — <?= htmlspecialchars($t['jouw_bestelling']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/start.css">
</head>
<body data-pagina="overzicht" class="overzicht-scherm">
<div class="bg-stippen"></div>

<?php render_header($lang, $t, true, 'menu.php', false); ?>

<div class="overzicht-wrapper">

    <?php if (!$heeft_items): ?>
        <!-- dit toon ik als de winkelwagen leeg is -->
        <div class="overzicht-leeg">
            <?= ICON_CART ?>
            <h2><?= htmlspecialchars($t['bestelling_leeg']) ?></h2>
            <p><?= htmlspecialchars($t['bestelling_leeg_sub']) ?></p>
            <a href="menu.php" class="btn btn-primair" style="margin-top:16px">
                <?= ICON_LEAF ?>
                <?= htmlspecialchars($t['naar_menu']) ?>
            </a>
        </div>

    <?php else: ?>
        <!-- de losse producten in de bestelling -->
        <div class="bestelling-lijst">
            <?php foreach ($cart as $item):
                $item_totaal = ($item['prijs'] + array_sum(array_column($item['dips'], 'prijs'))) * $item['aantal'];
            ?>
                <div class="bestelling-item">

                    <!-- kleine foto van het product of een categorie icoontje als er geen foto is -->
                    <?php $afb_pad_item = product_afbeelding_pad($item['product_id']); ?>
                    <div class="item-afb afb-<?= htmlspecialchars($item['categorie']) ?>">
                        <?php if ($afb_pad_item): ?>
                            <img src="<?= htmlspecialchars($afb_pad_item) ?>"
                                 alt="<?= htmlspecialchars($item['naam']) ?>"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
                        <?php else: ?>
                            <?= cat_icoon($item['categorie']) ?>
                        <?php endif; ?>
                    </div>

                    <!-- naam, dips en prijs per stuk -->
                    <div class="item-info">
                        <div class="item-naam"><?= htmlspecialchars($item['naam']) ?></div>
                        <?php if (!empty($item['dips'])): ?>
                            <div class="item-dips">
                                <?= htmlspecialchars($t['met_dips']) ?>:
                                <?= htmlspecialchars(implode(', ', array_column($item['dips'], 'naam'))) ?>
                            </div>
                        <?php endif; ?>
                        <div class="item-prijs-stuk"><?= prijs_formaat($item['prijs'], $lang) ?> p.st.</div>
                    </div>

                    <!-- de min en plus knop om het aantal aan te passen, werkt via een form post -->
                    <div class="item-aantal-ctrl">
                        <form method="POST" action="overzicht.php" style="display:contents">
                            <input type="hidden" name="actie"   value="update">
                            <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id']) ?>">
                            <input type="hidden" name="aantal"  value="<?= $item['aantal'] - 1 ?>">
                            <button type="submit" class="item-min" aria-label="Minder"><?= ICON_MINUS ?></button>
                        </form>
                        <span class="item-aantal-getal"><?= $item['aantal'] ?></span>
                        <form method="POST" action="overzicht.php" style="display:contents">
                            <input type="hidden" name="actie"   value="update">
                            <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id']) ?>">
                            <input type="hidden" name="aantal"  value="<?= $item['aantal'] + 1 ?>">
                            <button type="submit" class="item-plus" aria-label="Meer"><?= ICON_PLUS ?></button>
                        </form>
                    </div>

                    <!-- het totaalbedrag voor dit product inclusief dips en aantal -->
                    <div class="item-totaal"><?= prijs_formaat($item_totaal, $lang) ?></div>

                    <!-- knop om het product helemaal uit de bestelling te verwijderen -->
                    <form method="POST" action="overzicht.php">
                        <input type="hidden" name="actie"   value="verwijder">
                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id']) ?>">
                        <button type="submit" class="item-verwijder" aria-label="<?= htmlspecialchars($t['verwijder']) ?>">
                            <?= ICON_TRASH ?>
                        </button>
                    </form>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- het subtotaal en totaal onderaan de bestelling -->
        <div class="totaal-sectie">
            <div class="totaal-rij">
                <span class="totaal-label"><?= htmlspecialchars($t['subtotaal']) ?></span>
                <span style="font-size:16px;font-weight:600"><?= prijs_formaat($totaal, $lang) ?></span>
            </div>
            <div class="totaal-rij">
                <span class="totaal-label"><?= htmlspecialchars($t['incl_btw']) ?></span>
                <span style="font-size:13px;color:var(--tekst-muted)">
                    BTW: <?= prijs_formaat($totaal * 0.09 / 1.09, $lang) ?>
                </span>
            </div>
            <div class="totaal-rij totaal-groot">
                <span class="totaal-label" style="color:var(--wit);font-weight:600;font-size:17px">
                    <?= htmlspecialchars($t['totaal']) ?>
                </span>
                <span class="totaal-bedrag"><?= prijs_formaat($totaal, $lang) ?></span>
            </div>
        </div>

    <?php endif; ?>

</div>

<!-- knoppen onderaan om terug te gaan of door te gaan naar betalen -->
<?php if ($heeft_items): ?>
    <div class="overzicht-balk">
        <a href="menu.php" class="btn btn-ghost" style="flex:0 0 auto">
            <?= ICON_PLUS ?>
            <?= htmlspecialchars($t['naar_menu']) ?>
        </a>
        <a href="betaal.php" class="btn btn-primair" style="flex:1">
            <?= ICON_CART ?>
            <?= htmlspecialchars($t['doorgaan_betalen']) ?>
        </a>
    </div>
<?php endif; ?>

<script src="assets/js/app.js"></script>
</body>
</html>
