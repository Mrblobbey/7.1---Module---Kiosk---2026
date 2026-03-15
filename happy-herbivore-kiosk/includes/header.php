<?php
// in dit bestand staan alle icoontjes als svg code
// ook de functie die de header bovenaan de pagina tekent
// en een functie die het pad naar de productfoto ophaalt
// dit zijn de icoontjes die ik overal gebruik
define('ICON_BACK', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>');
define('ICON_CART', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>');
define('ICON_CLOSE', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>');
define('ICON_PLUS', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>');
define('ICON_MINUS', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>');
define('ICON_CHECK', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>');
define('ICON_TRASH', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>');
define('ICON_LEAF', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22c0 0 4-2 9-7s7-9 7-9S9 4 4 9 2 22 2 22z"/><path d="M22 2L12 12"/></svg>');

// dit zijn de icoontjes voor elke categorie in het menu
define('ICON_CAT_ONTBIJT',   '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="20" cy="20" r="7"/><line x1="20" y1="4" x2="20" y2="8"/><line x1="20" y1="32" x2="20" y2="36"/><line x1="4" y1="20" x2="8" y2="20"/><line x1="32" y1="20" x2="36" y2="20"/><line x1="8.3" y1="8.3" x2="11.2" y2="11.2"/><line x1="28.8" y1="28.8" x2="31.7" y2="31.7"/><line x1="31.7" y1="8.3" x2="28.8" y2="11.2"/><line x1="11.2" y1="28.8" x2="8.3" y2="31.7"/></svg>');
define('ICON_CAT_BOWLS',     '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20 Q7 32 20 32 Q33 32 33 20"/><line x1="5" y1="20" x2="35" y2="20"/><line x1="20" y1="32" x2="20" y2="36"/><line x1="15" y1="36" x2="25" y2="36"/><path d="M13 17 Q14 12 20 11 Q26 12 27 17"/></svg>');
define('ICON_CAT_HANDHELDS', '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="14" width="24" height="14" rx="4"/><path d="M8 18 Q20 12 32 18"/><line x1="12" y1="22" x2="28" y2="22"/><line x1="12" y1="26" x2="24" y2="26"/></svg>');
define('ICON_CAT_SIDES',     '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="12" y="8" width="16" height="22" rx="3"/><line x1="16" y1="8" x2="16" y2="30"/><line x1="20" y1="8" x2="20" y2="30"/><line x1="24" y1="8" x2="24" y2="30"/><line x1="10" y1="30" x2="30" y2="30"/></svg>');
define('ICON_CAT_DIPS',      '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13 10 L11 30 Q11 32 20 32 Q29 32 29 30 L27 10 Z"/><line x1="13" y1="10" x2="27" y2="10"/><path d="M20 10 L20 6 Q20 4 22 4"/><line x1="15" y1="18" x2="25" y2="18"/></svg>');
define('ICON_CAT_DRANKEN',   '<svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13 10 L11 32 Q11 34 20 34 Q29 34 29 32 L27 10 Z"/><line x1="13" y1="10" x2="27" y2="10"/><path d="M23 6 Q26 7 24 10"/><path d="M20 4 Q23 5 21 8"/></svg>');

// icoontjes voor de betaalmethodes
define('ICON_PIN',    '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="40" height="28" rx="4"/><line x1="4" y1="18" x2="44" y2="18"/><line x1="10" y1="28" x2="18" y2="28"/><line x1="10" y1="33" x2="14" y2="33"/><circle cx="36" cy="30" r="5"/></svg>');
define('ICON_CASH',   '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="12" width="40" height="24" rx="3"/><circle cx="24" cy="24" r="6"/><line x1="4" y1="18" x2="12" y2="18"/><line x1="36" y1="18" x2="44" y2="18"/><line x1="4" y1="30" x2="12" y2="30"/><line x1="36" y1="30" x2="44" y2="30"/></svg>');

// met deze functie haal ik het juiste icoontje op voor een categorie
function cat_icoon(string $categorie): string {
    $map = [
        'ontbijt'   => ICON_CAT_ONTBIJT,
        'bowls'     => ICON_CAT_BOWLS,
        'handhelds' => ICON_CAT_HANDHELDS,
        'sides'     => ICON_CAT_SIDES,
        'dips'      => ICON_CAT_DIPS,
        'dranken'   => ICON_CAT_DRANKEN,
    ];
    return $map[$categorie] ?? ICON_LEAF;
}

function render_header(string $lang, array $t, bool $toon_terug = false, string $terug_url = '#', bool $toon_cart = false): void {
    $cart_items = cart_aantal_items();
    ?>
    <header class="kiosk-header">
        <div class="header-links">
            <?php if ($toon_terug): ?>
                <a href="<?= htmlspecialchars($terug_url) ?>" class="btn-terug">
                    <?= ICON_BACK ?>
                    <span><?= htmlspecialchars($t['terug']) ?></span>
                </a>
            <?php else: ?>
                <div class="header-links-spacer"></div>
            <?php endif; ?>
        </div>
        <div class="header-logo">
            <img src="assets/images/logo_complete.png" alt="Happy Herbivore" class="header-logo-img">
        </div>
        <div class="header-rechts">
            <div class="taal-switcher">
                <?php foreach (['nl','en','de'] as $code): ?>
                    <a href="?setlang=<?= $code ?>" class="taal-btn<?= $lang === $code ? ' actief' : '' ?>" title="<?= strtoupper($code) ?>">
                        <?= taal_vlag_svg($code) ?>
                        <span class="taal-code"><?= strtoupper($code) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php if ($toon_cart && $cart_items > 0): ?>
                <a href="overzicht.php" class="header-cart-btn">
                    <?= ICON_CART ?>
                    <span class="cart-badge"><?= $cart_items ?></span>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <?php
}

// kleine vlaggetjes als svg die ik gebruik bij de taalwisselaar
function taal_vlag_svg(string $code): string {
    $vlaggen = [
        'nl' => '<svg viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="7" fill="#AE1C28"/><rect y="7" width="30" height="6" fill="#fff"/><rect y="13" width="30" height="7" fill="#21468B"/></svg>',
        'en' => '<svg viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="20" fill="#012169"/><path d="M0 0l30 20M30 0L0 20" stroke="#fff" stroke-width="3"/><path d="M0 0l30 20M30 0L0 20" stroke="#C8102E" stroke-width="2"/><path d="M15 0v20M0 10h30" stroke="#fff" stroke-width="6"/><path d="M15 0v20M0 10h30" stroke="#C8102E" stroke-width="3"/></svg>',
        'de' => '<svg viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="7" fill="#000"/><rect y="7" width="30" height="6" fill="#D00"/><rect y="13" width="30" height="7" fill="#FFCE00"/></svg>',
    ];
    return $vlaggen[$code] ?? '';
}

function product_afbeelding_pad(int $product_id): ?string {
    $basis = __DIR__ . '/../assets/images/products/';
    foreach (['png', 'webp', 'jpg'] as $ext) {
        $bestand = "product_{$product_id}.{$ext}";
        if (file_exists($basis . $bestand)) {
            return "assets/images/products/{$bestand}";
        }
    }
    return null;
}
