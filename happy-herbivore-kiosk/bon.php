<?php
// dit is de bevestigingspagina
// hier staat de ophaalcode zodat de klant weet wanneer ze geroepen worden
// na 20 seconden gaat het scherm vanzelf terug naar het begin
require_once 'includes/init.php';
require_once 'includes/header.php';
require_once 'includes/menu_data.php';
require_once 'config/database.php';

// als er geen bestelling is stuur ik terug naar het beginscherm
$cart    = $_SESSION['cart'] ?? [];
$methode = in_array($_GET['methode'] ?? '', ['pin','contant']) ? $_GET['methode'] : 'pin';

if (empty($cart) && empty($_SESSION['bon_cart'])) {
    header('Location: index.php');
    exit;
}

// ik sla de winkelmand op in de sessie zodat ik hem kan tonen na het leegmaken
if (!empty($cart)) {
    $_SESSION['bon_cart']    = $cart;
    $_SESSION['bon_totaal']  = cart_totaal();
    $_SESSION['bon_methode'] = $methode;
    $_SESSION['ophaalcode']  = rand(100, 999);
    cart_leegmaken();
    // cart_leegmaken verwijdert ook de ophaalcode dus ik zet hem hier terug
    $_SESSION['ophaalcode']  = $_SESSION['ophaalcode'] ?? rand(100, 999);
}

$bon_cart    = $_SESSION['bon_cart']   ?? [];
$bon_totaal  = $_SESSION['bon_totaal'] ?? 0.0;
$bon_methode = $_SESSION['bon_methode'] ?? $methode;
$ophaalcode  = $_SESSION['ophaalcode'] ?? rand(100, 999);
$bon_info    = $bon_methode === 'pin' ? $t['bon_info_pin'] : $t['bon_info_contant'];
$betaal_label= $bon_methode === 'pin' ? $t['pin_label']   : $t['contant_label'];

// ik probeer de bestelling op te slaan in de database
// als dat mislukt gaat de bon gewoon verder, de klant merkt er niets van
try {
    $conn = db_verbinden();
    if ($conn && !empty($bon_cart)) {
        $besteltype = $_SESSION['besteltype'] ?? 'hier_eten';
        $stmt = $conn->prepare(
            "INSERT INTO bestellingen (besteltype, taal, totaal_prijs, betaalmethode, ophaalcode)
             VALUES (?,?,?,?,?)"
        );
        if ($stmt) {
            $stmt->bind_param('ssdsi', $besteltype, $lang, $bon_totaal, $bon_methode, $ophaalcode);
            $stmt->execute();
            $bestelling_id = $conn->insert_id;
            $stmt->close();

            foreach ($bon_cart as $item) {
                $stmt2 = $conn->prepare(
                    "INSERT INTO bestelling_regels (bestelling_id, product_id, product_naam, aantal, prijs_per_stuk)
                     VALUES (?,?,?,?,?)"
                );
                if ($stmt2) {
                    $stmt2->bind_param('iisid',
                        $bestelling_id,
                        $item['product_id'],
                        $item['naam'],
                        $item['aantal'],
                        $item['prijs']
                    );
                    $stmt2->execute();
                    $stmt2->close();
                }
            }
        }
        $conn->close();
    }
} catch (Throwable $e) {
    // als de database er niet is sla ik gewoon niets op maar de bon werkt wel
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore — <?= htmlspecialchars($t['bedankt']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/start.css">
</head>
<body data-pagina="bon" class="bon-scherm">
<div class="bg-stippen"></div>

<!-- taalwisselaar ook op de bon pagina zodat de klant nog van taal kan wisselen -->
<div style="position:fixed;top:16px;right:16px;z-index:50">
    <div class="taal-switcher">
        <?php foreach (['nl','en','de'] as $code): ?>
            <a href="?setlang=<?= $code ?>&methode=<?= $bon_methode ?>"
               class="taal-btn<?= $lang === $code ? ' actief' : '' ?>"
               title="<?= strtoupper($code) ?>">
                <?= taal_vlag_svg($code) ?>
                <span class="taal-code"><?= strtoupper($code) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- groen vinkje als bevestiging -->
<div class="bon-check"><?= ICON_CHECK ?></div>

<h1 class="bon-bedankt"><?= htmlspecialchars($t['bedankt']) ?></h1>
<p class="bon-sub"><?= htmlspecialchars($t['bon_sub']) ?></p>

<!-- de ophaalcode die de klant moet onthouden of laten zien -->
<div class="bon-code-kaart">
    <div class="bon-code-label"><?= htmlspecialchars($t['ophaalcode']) ?></div>
    <div class="bon-code-nummer"><?= str_pad($ophaalcode, 3, '0', STR_PAD_LEFT) ?></div>
</div>

<p class="bon-info"><?= htmlspecialchars($bon_info) ?></p>
<p class="bon-betaalwijze">
    <?= htmlspecialchars($t['betaald_met']) ?>:
    <strong><?= htmlspecialchars($betaal_label) ?></strong>
</p>

<!-- kort overzicht van wat er besteld is -->
<?php if (!empty($bon_cart)): ?>
    <div class="bon-items-lijst">
        <?php foreach ($bon_cart as $item):
            $item_totaal = ($item['prijs'] + array_sum(array_column($item['dips'] ?? [], 'prijs'))) * $item['aantal'];
        ?>
            <div class="bon-item-rij">
                <span><?= $item['aantal'] ?>× <?= htmlspecialchars($item['naam']) ?></span>
                <span><?= prijs_formaat($item_totaal, $lang) ?></span>
            </div>
        <?php endforeach; ?>
        <div class="bon-totaal-rij">
            <span><?= htmlspecialchars($t['totaal']) ?></span>
            <span><?= prijs_formaat($bon_totaal, $lang) ?></span>
        </div>
    </div>
<?php endif; ?>

<a href="index.php" class="btn btn-groen" style="font-size:18px;padding:18px 48px;margin-top:8px">
    <?= htmlspecialchars($t['nieuwe_bestelling']) ?>
</a>

<p class="bon-countdown">
    <?= htmlspecialchars($t['auto_terug']) ?>
    <span id="bon-countdown-getal">20</span>
    <?= htmlspecialchars($t['seconden']) ?>
</p>

<script src="assets/js/app.js"></script>
</body>
</html>
