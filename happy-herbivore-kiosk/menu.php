<?php
// dit is de menukaart
// ik haal de producten op en toon ze per categorie
require_once 'includes/init.php';
require_once 'includes/header.php';
require_once 'includes/menu_data.php';

$alle_producten   = menu_ophalen($lang);
$per_categorie    = menu_gecategoriseerd($alle_producten);
$cart_items_count = cart_aantal_items();
$cart_totaal_now  = cart_totaal();

// ik bepaal de volgorde van de categorietabs en de labels daarvoor
$categorie_volgorde = ['ontbijt', 'bowls', 'handhelds', 'sides', 'dips', 'dranken'];
$cat_labels = [
    'ontbijt'   => $t['ontbijt'],
    'bowls'     => $t['bowls'],
    'handhelds' => $t['handhelds'],
    'sides'     => $t['sides'],
    'dips'      => $t['dips'],
    'dranken'   => $t['dranken'],
];

$toon_terug  = true;
$terug_url   = 'start.php';
$toon_cart   = true;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore — <?= htmlspecialchars($t['menu_titel']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/start.css">
</head>
<body data-pagina="menu" class="menu-scherm">
<div class="bg-stippen"></div>

<?php render_header($lang, $t, true, 'start.php', true); ?>

<!-- tabs om te filteren per categorie -->
<div class="cat-tabs-wrapper">
    <div class="cat-tabs">
        <button class="cat-tab actief" data-cat="alle">
            <?= ICON_LEAF ?>
            <span><?= htmlspecialchars($t['alle']) ?></span>
        </button>
        <?php foreach ($categorie_volgorde as $cat):
            if (empty($per_categorie[$cat])) continue; ?>
            <button class="cat-tab" data-cat="<?= $cat ?>">
                <?= cat_icoon($cat) ?>
                <span><?= htmlspecialchars($cat_labels[$cat] ?? $cat) ?></span>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<!-- het grid met alle producten -->
<div class="menu-grid-wrapper">
    <?php foreach ($categorie_volgorde as $cat):
        if (empty($per_categorie[$cat])) continue; ?>

        <section class="categorie-sectie" data-cat="<?= $cat ?>">
            <h2 class="categorie-kop"><?= htmlspecialchars($cat_labels[$cat] ?? $cat) ?></h2>
            <div class="product-grid">

                <?php foreach ($per_categorie[$cat] as $i => $product): ?>
                    <div class="product-kaart" style="animation-delay: <?= $i * 0.05 ?>s">

                        <!-- foto van het product, of een placeholder als er nog geen foto is -->
                        <?php $afb_pad = product_afbeelding_pad($product['id']); ?>
                        <a href="product.php?id=<?= $product['id'] ?>" class="product-afbeelding afb-<?= $cat ?>">
                            <?php if ($afb_pad): ?>
                                <img src="<?= htmlspecialchars($afb_pad) ?>" alt="<?= htmlspecialchars($product['naam']) ?>" loading="lazy">
                            <?php else: ?>
                                <?= cat_icoon($cat) ?>
                                <span class="placeholder-tekst">afbeelding volgt</span>
                            <?php endif; ?>
                        </a>

                        <!-- naam, badges en beschrijving van het product -->
                        <a href="product.php?id=<?= $product['id'] ?>" class="product-body">
                            <div class="product-naam"><?= htmlspecialchars($product['naam']) ?></div>
                            <div class="product-badges">
                                <?php if ($product['is_vegan']): ?>
                                    <span class="badge badge-vegan"><?= htmlspecialchars($t['vegan_label']) ?></span>
                                <?php elseif ($product['is_vegetarisch']): ?>
                                    <span class="badge badge-veg"><?= htmlspecialchars($t['vegetarisch_label']) ?></span>
                                <?php endif; ?>
                                <span class="badge badge-kcal"><?= $product['kcal'] ?> <?= $t['kcal'] ?></span>
                            </div>
                            <p class="product-beschrijving"><?= htmlspecialchars($product['beschrijving']) ?></p>
                        </a>

                        <!-- prijs en de plus knop om toe te voegen -->
                        <div class="product-footer">
                            <span class="product-prijs"><?= prijs_formaat($product['prijs'], $lang) ?></span>
                            <button
                                class="btn-add-klein"
                                data-id="<?= $product['id'] ?>"
                                data-naam="<?= htmlspecialchars(addslashes($product['naam'])) ?>"
                                data-prijs="<?= $product['prijs'] ?>"
                                data-kcal="<?= $product['kcal'] ?>"
                                data-categorie="<?= $cat ?>"
                                aria-label="<?= htmlspecialchars($t['toevoegen']) ?>"
                            ><?= ICON_PLUS ?></button>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        </section>

    <?php endforeach; ?>
</div>

<!-- de balk onderaan met het totaal en de knop naar het overzicht -->
<div class="cart-balk" id="cart-balk" style="<?= $cart_items_count === 0 ? 'display:none' : '' ?>">
    <div class="cart-balk-info">
        <span class="cart-balk-aantal" id="cart-balk-aantal">
            <?= $cart_items_count ?> item<?= $cart_items_count !== 1 ? 's' : '' ?>
        </span>
        <span class="cart-balk-totaal" id="cart-balk-totaal">
            <?= prijs_formaat($cart_totaal_now, $lang) ?>
        </span>
    </div>
    <a href="overzicht.php" class="btn-bekijk">
        <?= ICON_CART ?>
        <?= htmlspecialchars($t['bekijk_bestelling']) ?>
    </a>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
