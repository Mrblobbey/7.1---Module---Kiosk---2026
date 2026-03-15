<?php
// op dit scherm kiest de klant eerst de taal
// daarna of ze hier eten of meenemen
// daarna gaat het door naar het menu
// sessie en taal worden geladen via init.php
require_once 'includes/init.php';
require_once 'includes/header.php';   // header.php bevat alleen functies, het tekent hier nog niets

// als er een type is meegegeven sla ik dat op en ga ik naar het menu
// dit moet voor de html output anders krijg ik een headers error
if (isset($_GET['type']) && in_array($_GET['type'], ['hier_eten', 'meenemen'])) {
    $_SESSION['besteltype'] = $_GET['type'];
    header('Location: menu.php');
    exit;
}

// de drie talen die ik ondersteuning
$talen = [
    'nl' => 'Nederlands',
    'en' => 'English',
    'de' => 'Deutsch',
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Happy Herbivore Kiosk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/start.css">
</head>
<body data-pagina="start">
<div class="bg-stippen"></div>

<?php render_header($lang, $t, false, '', false); ?>

<main class="start-main">

    <!-- Welkomstgroet -->
    <div class="start-welkom">
        <p class="start-welkom-tekst"><?= htmlspecialchars($t['slogan']) ?></p>
    </div>

    <!-- ── STAP 1 : TAAL ── -->
    <section class="start-sectie" id="sectie-taal">

        <div class="start-stap-kop">
            <span class="start-stap-nr">01</span>
            <h2 class="start-stap-titel"><?= htmlspecialchars($t['kies_taal']) ?></h2>
        </div>

        <div class="taal-rij">
            <?php foreach ($talen as $code => $naam): ?>
                <a
                    href="?setlang=<?= $code ?>"
                    class="taal-kaart<?= $lang === $code ? ' actief' : '' ?>"
                    aria-label="<?= htmlspecialchars($naam) ?>"
                >
                    <div class="taal-kaart-vlag">
                        <?= taal_vlag_svg($code) ?>
                    </div>
                    <span class="taal-kaart-naam"><?= htmlspecialchars($naam) ?></span>
                    <?php if ($lang === $code): ?>
                        <span class="taal-kaart-check"><?= ICON_CHECK ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </section>

    <!-- SCHEIDINGSLIJN -->
    <div class="start-divider">
        <span class="start-divider-lijn"></span>
        <img src="assets/images/logo_dino.png" alt="" class="start-divider-dino">
        <span class="start-divider-lijn"></span>
    </div>

    <!-- ── STAP 2 : BESTELTYPE ── -->
    <section class="start-sectie" id="sectie-besteltype">

        <div class="start-stap-kop">
            <span class="start-stap-nr">02</span>
            <h2 class="start-stap-titel"><?= htmlspecialchars($t['besteltype_vraag']) ?></h2>
        </div>

        <div class="besteltype-rij">

            <!-- HIER ETEN -->
            <a href="start.php?type=hier_eten" class="bt-kaart bt-hier-eten">
                <div class="bt-kaart-glow"></div>
                <div class="bt-kaart-icoon">
                    <svg viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 48 L12 30 Q12 11 28 11 Q44 11 44 30 L44 48"/>
                        <line x1="6" y1="48" x2="50" y2="48"/>
                        <path d="M20 30 Q20 22 28 22 Q36 22 36 30"/>
                        <line x1="16" y1="38" x2="40" y2="38"/>
                        <line x1="28" y1="6" x2="28" y2="11"/>
                    </svg>
                </div>
                <div class="bt-kaart-tekst">
                    <span class="bt-kaart-naam"><?= htmlspecialchars($t['hier_eten']) ?></span>
                    <span class="bt-kaart-sub"><?= htmlspecialchars($t['hier_eten_sub']) ?></span>
                </div>
                <span class="bt-kaart-pijl">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </span>
            </a>

            <!-- MEENEMEN -->
            <a href="start.php?type=meenemen" class="bt-kaart bt-meenemen">
                <div class="bt-kaart-glow"></div>
                <div class="bt-kaart-icoon">
                    <svg viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 17 L14 47 Q14 50 28 50 Q42 50 42 47 L37 17 Z"/>
                        <line x1="19" y1="17" x2="37" y2="17"/>
                        <path d="M23 17 L23 11 Q23 7 28 7 Q33 7 33 11 L33 17"/>
                        <line x1="20" y1="27" x2="36" y2="27"/>
                        <line x1="19" y1="36" x2="35" y2="36"/>
                    </svg>
                </div>
                <div class="bt-kaart-tekst">
                    <span class="bt-kaart-naam"><?= htmlspecialchars($t['meenemen']) ?></span>
                    <span class="bt-kaart-sub"><?= htmlspecialchars($t['meenemen_sub']) ?></span>
                </div>
                <span class="bt-kaart-pijl">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </span>
            </a>

        </div>

    </section>

</main>

<script src="assets/js/app.js"></script>
</body>
</html>
