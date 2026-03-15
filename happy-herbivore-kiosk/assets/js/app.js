// dit is het javascript bestand voor de kiosk
// onderaan roep ik per pagina de juiste functies aan



function idle_init() {
    var scherm = document.getElementById('idle-scherm');
    if (!scherm) return;

    // als er geklikt wordt ga ik naar start.php
    scherm.addEventListener('click', function () {
        window.location.href = 'start.php';
    });
}

var IDLE_TIMEOUT_MS = 90000; // 90 seconden
var idleTimer = null;

function idle_timer_reset() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(function () {
        // als er te lang niets gebeurt ga ik terug naar het idle scherm
        // maar alleen als ik niet al op idle of start ben
        var pagina = document.body.dataset.pagina;
        if (pagina && pagina !== 'idle' && pagina !== 'start') {
            window.location.href = 'index.php';
        }
    }, IDLE_TIMEOUT_MS);
}

function idle_timer_init() {
    ['touchstart', 'touchmove', 'mousedown', 'mousemove', 'keydown', 'scroll'].forEach(function (evt) {
        document.addEventListener(evt, idle_timer_reset, { passive: true });
    });
    idle_timer_reset();
}

function taal_btn_init() {
    document.querySelectorAll('.taal-keuze-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var code = btn.dataset.lang;
            document.querySelectorAll('.taal-keuze-btn').forEach(function (b) {
                b.classList.remove('actief');
            });
            btn.classList.add('actief');
            window.location.href = '?setlang=' + code;
        });
    });
}

function besteltype_init() {
    document.querySelectorAll('.besteltype-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = btn.dataset.type;
            btn.classList.add('gekozen');
            setTimeout(function () {
                window.location.href = 'menu.php?type=' + type;
            }, 220);
        });
    });
}

function menu_filter_init() {
    var tabs    = document.querySelectorAll('.cat-tab');
    var secties = document.querySelectorAll('.categorie-sectie');

    if (!tabs.length) return;

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var filter = tab.dataset.cat;

            tabs.forEach(function (t) { t.classList.remove('actief'); });
            tab.classList.add('actief');

            secties.forEach(function (s) {
                if (filter === 'alle' || s.dataset.cat === filter) {
                    s.classList.remove('verborgen');
                } else {
                    s.classList.add('verborgen');
                }
            });

            // als ik van categorie wissel scroll ik terug naar boven
            var wrapper = document.querySelector('.menu-grid-wrapper');
            if (wrapper) wrapper.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
}

function product_kaart_init() {
    // de plus knop op een productkaart voegt direct toe zonder naar de detailpagina te gaan
    document.querySelectorAll('.btn-add-klein').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // ik stop de klik hier zodat de kaart zelf niet ook opent
            var id        = btn.dataset.id;
            var naam      = btn.dataset.naam;
            var prijs     = btn.dataset.prijs;
            var categorie = btn.dataset.categorie;
            var kcal      = btn.dataset.kcal || 0;

            cart_snel_toevoegen(parseInt(id), naam, parseFloat(prijs), parseInt(kcal), categorie, btn);
        });
    });
}

function cart_snel_toevoegen(id, naam, prijs, kcal, categorie, knop) {
    fetch('api/cart_add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: id, naam: naam, prijs: prijs, kcal: kcal, categorie: categorie, aantal: 1, dips: [] })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.succes) {
            // ik verander de knop even naar een vinkje als feedback
            var orig = knop.innerHTML;
            knop.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
            knop.style.background = 'var(--groen)';
            setTimeout(function () {
                knop.innerHTML = orig;
                knop.style.background = '';
            }, 800);

            // daarna werk ik de winkelwagen balk onderaan bij
            cart_balk_update(data.cart_aantal, data.cart_totaal);
        }
    })
    .catch(function () {
        // als de fetch mislukt ga ik gewoon naar de productpagina
        window.location.href = 'product.php?id=' + id;
    });
}

function cart_balk_update(aantal, totaal) {
    var balk   = document.getElementById('cart-balk');
    var aantalEl = document.getElementById('cart-balk-aantal');
    var totaalEl = document.getElementById('cart-balk-totaal');
    var badgeEl  = document.querySelector('.cart-badge');

    if (!balk) return;

    if (aantal > 0) {
        balk.style.display = 'flex';
        if (aantalEl) aantalEl.textContent = aantal + (aantal === 1 ? ' item' : ' items');
        if (totaalEl) totaalEl.textContent = totaal;
        if (badgeEl) badgeEl.textContent = aantal;
    } else {
        balk.style.display = 'none';
    }
}

var geselecteerde_dips = [];

function dip_init() {
    document.querySelectorAll('.dip-kaart').forEach(function (kaart) {
        kaart.addEventListener('click', function () {
            var id    = parseInt(kaart.dataset.id);
            var naam  = kaart.dataset.naam;
            var prijs = parseFloat(kaart.dataset.prijs);

            var idx = geselecteerde_dips.findIndex(function (d) { return d.id === id; });

            if (idx > -1) {
                geselecteerde_dips.splice(idx, 1);
                kaart.classList.remove('geselecteerd');
            } else {
                geselecteerde_dips.push({ id: id, naam: naam, prijs: prijs });
                kaart.classList.add('geselecteerd');
            }

            // ik pas het vinkje aan op basis van of de dip geselecteerd is
            var checkEl = kaart.querySelector('.dip-check');
            if (checkEl) {
                checkEl.innerHTML = kaart.classList.contains('geselecteerd')
                    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
                    : '';
            }

            product_balk_prijs_update();
        });
    });
}

var product_aantal = 1;

function aantal_init() {
    var aantalEl = document.getElementById('aantal-waarde');
    var minBtn   = document.getElementById('aantal-min');
    var plusBtn  = document.getElementById('aantal-plus');

    if (!aantalEl) return;

    if (minBtn) {
        minBtn.addEventListener('click', function () {
            if (product_aantal > 1) {
                product_aantal--;
                aantalEl.textContent = product_aantal;
                product_balk_prijs_update();
            }
        });
    }
    if (plusBtn) {
        plusBtn.addEventListener('click', function () {
            if (product_aantal < 10) {
                product_aantal++;
                aantalEl.textContent = product_aantal;
                product_balk_prijs_update();
            }
        });
    }
}

function product_balk_prijs_update() {
    var basisPrijs = parseFloat(document.getElementById('product-basis-prijs')?.value || 0);
    var dipsTotaal = geselecteerde_dips.reduce(function (s, d) { return s + d.prijs; }, 0);
    var totaal     = (basisPrijs + dipsTotaal) * product_aantal;

    var balkPrijsEl = document.getElementById('product-balk-prijs');
    var aantalInput = document.getElementById('aantal-input');
    var dipsInput   = document.getElementById('dips-input');
    var separator   = document.documentElement.lang === 'en' ? '.' : ',';

    if (balkPrijsEl) balkPrijsEl.textContent = '€' + totaal.toFixed(2).replace('.', separator);
    if (aantalInput) aantalInput.value = product_aantal;
    if (dipsInput)   dipsInput.value   = JSON.stringify(geselecteerde_dips);
}

function overzicht_init() {
    // knoppen om een product te verwijderen
    document.querySelectorAll('.item-verwijder').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cartId = btn.dataset.cartId;
            fetch('api/cart_remove.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_id: cartId })
            })
            .then(function () { location.reload(); });
        });
    });

    // plus en min knoppen om het aantal aan te passen
    document.querySelectorAll('.item-min, .item-plus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cartId = btn.dataset.cartId;
            var delta  = btn.classList.contains('item-min') ? -1 : 1;
            var aantalEl = btn.closest('.item-aantal-ctrl').querySelector('.item-aantal-getal');
            var huidigAantal = parseInt(aantalEl?.textContent || 1);
            var nieuwAantal  = huidigAantal + delta;

            fetch('api/cart_update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_id: cartId, aantal: nieuwAantal })
            })
            .then(function () { location.reload(); });
        });
    });
}

function betaal_init() {
    document.querySelectorAll('.betaal-optie').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var methode  = btn.dataset.methode;
            var overlay  = document.getElementById('betaal-overlay');

            if (methode === 'pin') {
                // bij pin toon ik de laad animatie en stuur ik door naar de bon
                if (overlay) overlay.classList.add('actief');
                setTimeout(function () {
                    window.location.href = 'bon.php?methode=pin';
                }, 3000);
            } else {
                // bij contant ga ik meteen naar de bon zonder animatie
                window.location.href = 'bon.php?methode=contant';
            }
        });
    });
}

function bon_countdown_init() {
    var teller    = document.getElementById('bon-countdown-getal');
    if (!teller) return;

    var seconden = parseInt(teller.textContent || 20);

    var interval = setInterval(function () {
        seconden--;
        teller.textContent = seconden;
        if (seconden <= 0) {
            clearInterval(interval);
            window.location.href = 'index.php';
        }
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function () {
    var pagina = document.body.dataset.pagina;

    idle_init();

    if (pagina && pagina !== 'idle' && pagina !== 'start') {
        idle_timer_init();
    }

    switch (pagina) {
        case 'start':
            taal_btn_init();
            besteltype_init();
            break;
        case 'menu':
            menu_filter_init();
            product_kaart_init();
            break;
        case 'product':
            dip_init();
            aantal_init();
            product_balk_prijs_update();
            break;
        case 'overzicht':
            overzicht_init();
            break;
        case 'betaal':
            betaal_init();
            break;
        case 'bon':
            bon_countdown_init();
            break;
    }
});
