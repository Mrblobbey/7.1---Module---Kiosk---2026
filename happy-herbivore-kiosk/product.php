<?php
// dit is de detailpagina van een product
// hier kan de klant het aantal kiezen en een dip toevoegen
require_once 'includes/init.php';
require_once 'includes/header.php';
require_once 'includes/menu_data.php';

$product_id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$alle_producten = menu_ophalen($lang);
$product        = product_ophalen($product_id, $alle_producten);
$dips_lijst     = menu_dips($alle_producten);

if (!$product) {
    header('Location: menu.php');
    exit;
}

// als het formulier verstuurd is voeg ik het product toe aan de winkelmand
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aantal       = max(1, min(10, (int)($_POST['aantal'] ?? 1)));
    $dips_raw     = $_POST['dips'] ?? '[]';
    $dips_gekozen = [];

    $dips_decoded = json_decode($dips_raw, true);
    if (is_array($dips_decoded)) {
        foreach ($dips_decoded as $dip_item) {
            $dip_obj = product_ophalen((int)($dip_item['id'] ?? 0), $alle_producten);
            if ($dip_obj && $dip_obj['categorie'] === 'dips') {
                $dips_gekozen[] = [
                    'id'    => $dip_obj['id'],
                    'naam'  => $dip_obj['naam'],
                    'prijs' => $dip_obj['prijs'],
                ];
            }
        }
    }

    cart_toevoegen(
        $product['id'],
        $product['naam'],
        $product['prijs'],
        $product['kcal'],
        $product['categorie'],
        $dips_gekozen,
        $aantal
    );

    header('Location: menu.php');
    exit;
}

$cat = $product['categorie'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore — <?= htmlspecialchars($product['naam']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/start.css">
</head>
<body data-pagina="product" class="product-scherm">
<div class="bg-stippen"></div>

<?php render_header($lang, $t, true, 'menu.php', true); ?>

<!-- CONTENT -->
<div class="product-detail-wrapper">

    <!-- grote foto van het product bovenaan de pagina -->
    <?php $afb_pad = product_afbeelding_pad($product['id']); ?>
    <div class="product-hero afb-<?= $cat ?>">
        <?php if ($afb_pad): ?>
            <img src="<?= htmlspecialchars($afb_pad) ?>"
                 alt="<?= htmlspecialchars($product['naam']) ?>"
                 style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;border-radius:inherit;">
        <?php else: ?>
            <?= cat_icoon($cat) ?>
            <span class="ph-tekst">afbeelding volgt</span>
        <?php endif; ?>
    </div>

    <!-- naam van het product en de vegan of vegetarisch badge -->
    <h1 class="product-detail-naam"><?= htmlspecialchars($product['naam']) ?></h1>

    <div class="product-detail-badges">
        <?php if ($product['is_vegan']): ?>
            <span class="badge badge-vegan"><?= htmlspecialchars($t['vegan_label']) ?></span>
        <?php elseif ($product['is_vegetarisch']): ?>
            <span class="badge badge-veg"><?= htmlspecialchars($t['vegetarisch_label']) ?></span>
        <?php endif; ?>
        <span class="badge badge-kcal"><?= $product['kcal'] ?> <?= $t['kcal'] ?></span>
    </div>

    <!-- korte omschrijving van het product -->
    <p class="product-detail-beschrijving"><?= htmlspecialchars($product['beschrijving']) ?></p>

    <!-- prijs van het product -->
    <div class="product-detail-prijs"><?= prijs_formaat($product['prijs'], $lang) ?></div>

    <!-- hier kan de klant het aantal aanpassen -->
    <div>
        <p style="font-size:14px;color:var(--tekst-muted);margin-bottom:10px;font-weight:500;"><?= htmlspecialchars($t['stuks_label']) ?></p>
        <div class="aantal-selector">
            <button class="aantal-btn" id="aantal-min" type="button"><?= ICON_MINUS ?></button>
            <span class="aantal-waarde" id="aantal-waarde">1</span>
            <button class="aantal-btn" id="aantal-plus" type="button"><?= ICON_PLUS ?></button>
        </div>
    </div>

    <!-- dips kiezen, alleen als het geen drankje of dip is -->
    <?php if (!in_array($cat, ['dranken', 'dips']) && !empty($dips_lijst)): ?>
        <div class="dips-sectie">
            <h3 class="dips-titel"><?= htmlspecialchars($t['kies_dips']) ?></h3>
            <p class="dips-sub"><?= htmlspecialchars($t['dip_per_stuk']) ?></p>

            <div class="dips-grid">
                <?php foreach ($dips_lijst as $dip): ?>
                    <div
                        class="dip-kaart"
                        data-id="<?= $dip['id'] ?>"
                        data-naam="<?= htmlspecialchars(addslashes($dip['naam'])) ?>"
                        data-prijs="<?= $dip['prijs'] ?>"
                    >
                        <div class="dip-icoon"><?= cat_icoon('dips') ?></div>
                        <div class="dip-info">
                            <div class="dip-naam"><?= htmlspecialchars($dip['naam']) ?></div>
                            <div class="dip-prijs"><?= prijs_formaat($dip['prijs'], $lang) ?></div>
                        </div>
                        <div class="dip-check"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- de balk onderaan om het product toe te voegen aan de bestelling -->
<form method="POST" action="product.php?id=<?= $product_id ?>">
    <input type="hidden" name="aantal" id="aantal-input" value="1">
    <input type="hidden" name="dips"   id="dips-input"   value="[]">
    <!-- de taal hoef ik hier niet mee te sturen want dat staat al in de sessie -->

    <div class="product-toevoegen-balk">
        <span class="product-balk-prijs" id="product-balk-prijs">
            <?= prijs_formaat($product['prijs'], $lang) ?>
        </span>
        <button type="submit" class="btn btn-primair" style="flex:1">
            <?= ICON_CART ?>
            <?= htmlspecialchars($t['voeg_toe_bestelling']) ?>
        </button>
    </div>
</form>

<!-- verborgen veld zodat javascript de basisprijs weet -->
<input type="hidden" id="product-basis-prijs" value="<?= $product['prijs'] ?>">

<script src="assets/js/app.js"></script>
</body>
</html>
