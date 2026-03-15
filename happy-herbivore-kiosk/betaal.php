<?php
// op dit scherm kiest de klant de betaalmethode
// ze kunnen kiezen uit pin of contant
require_once 'includes/init.php';
require_once 'includes/header.php';

// als de winkelmand leeg is heeft dit scherm geen zin, ik stuur terug naar het menu
if (cart_aantal_items() === 0) {
    header('Location: menu.php');
    exit;
}

$totaal = cart_totaal();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore — <?= htmlspecialchars($t['betaal_titel']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/start.css">
</head>
<body data-pagina="betaal" class="betaal-scherm">
<div class="bg-stippen"></div>

<?php render_header($lang, $t, true, 'overzicht.php', false); ?>

<div class="betaal-wrapper">

    <h1 class="betaal-titel"><?= htmlspecialchars($t['betaal_titel']) ?></h1>

    <!-- het totaalbedrag bovenaan tonen zodat de klant weet wat ze gaan betalen -->
    <div style="text-align:center;margin:-8px 0 8px">
        <span style="font-size:15px;color:var(--tekst-muted)"><?= htmlspecialchars($t['totaal']) ?>:&nbsp;</span>
        <span style="font-family:var(--font-heading);font-size:32px;color:var(--oranje-licht)">
            <?= prijs_formaat($totaal, $lang) ?>
        </span>
    </div>

    <div class="betaal-opties">

        <!-- de knop voor betalen met pin -->
        <button class="betaal-optie opt-pin" data-methode="pin">
            <div class="betaal-optie-icoon"><?= ICON_PIN ?></div>
            <div class="betaal-optie-tekst">
                <span class="betaal-optie-naam"><?= htmlspecialchars($t['pin']) ?></span>
                <span class="betaal-optie-sub"><?= htmlspecialchars($t['pin_sub']) ?></span>
            </div>
            <span class="betaal-optie-pijl">&#8250;</span>
        </button>

        <!-- de knop voor betalen met contant geld -->
        <button class="betaal-optie opt-contant" data-methode="contant">
            <div class="betaal-optie-icoon"><?= ICON_CASH ?></div>
            <div class="betaal-optie-tekst">
                <span class="betaal-optie-naam"><?= htmlspecialchars($t['contant']) ?></span>
                <span class="betaal-optie-sub"><?= htmlspecialchars($t['contant_sub']) ?></span>
            </div>
            <span class="betaal-optie-pijl">&#8250;</span>
        </button>

    </div>

</div>

<!-- dit scherm toon ik als de klant voor pin kiest, het simuleert de betaling -->
<div class="betaal-overlay" id="betaal-overlay">
    <div class="betaal-spinner"></div>
    <p class="betaal-overlay-tekst"><?= htmlspecialchars($t['betalen_verwerken']) ?></p>
    <p class="betaal-overlay-sub"><?= htmlspecialchars($t['pin_instructie']) ?></p>
    <button
        class="btn btn-ghost"
        style="margin-top:16px"
        onclick="document.getElementById('betaal-overlay').classList.remove('actief')"
    >
        <?= htmlspecialchars($t['annuleer']) ?>
    </button>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
