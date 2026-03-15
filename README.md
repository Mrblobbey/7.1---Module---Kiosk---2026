# 🌿 Happy Herbivore Kiosk

Een volledig zelfstandig bestelkiosk-systeem voor Happy Herbivore, gebouwd met PHP, MySQL en vanilla JavaScript. Het systeem werkt als een McDonald's-stijl zelfbedieningskiosk: van idle scherm tot bon met ophaalcode — volledig meertalig (NL, EN, DE).

---

## Schermen & flow

```
index.php   →   start.php   →   menu.php   →   product.php
(idle/video)    (taal + type)   (menukaart)    (detail + dips)
                                                     ↓
              index.php   ←   bon.php   ←   betaal.php   ←   overzicht.php
              (nieuw)         (ophaalcode)   (pin/contant)    (besteloverzicht)
```

| Bestand | Scherm |
|---|---|
| `index.php` | Idle scherm met video-achtergrond en "Tik om te bestellen" |
| `start.php` | Taal kiezen (NL/EN/DE) + Hier eten of Meenemen |
| `menu.php` | Menukaart met categorie-tabs en productgrid |
| `product.php` | Productdetail, dips selecteren, aantal kiezen |
| `overzicht.php` | Besteloverzicht met aantallen aanpassen of verwijderen |
| `betaal.php` | Betaalmethode kiezen: Pinpas of Contant |
| `bon.php` | Bevestiging met 3-cijferige ophaalcode + afteller |

---

## Installatie

### Vereisten
- PHP 8.0 of hoger
- MySQL 5.7 of hoger
- MAMP (Mac) of XAMPP (Windows/Mac)
- Webbrowser (kiosk modus aanbevolen)

### Stap 1 — Bestanden plaatsen

Pak de zip uit en plaats de map `happy-herbivore-kiosk` in je webserver root:

- **MAMP (Mac):** `/Applications/MAMP/htdocs/`
- **XAMPP:** `C:/xampp/htdocs/`

### Stap 2 — Database importeren

1. Open **phpMyAdmin** → `http://localhost/phpmyadmin` (MAMP: `http://localhost:8888/phpmyadmin`)
2. Klik op **Nieuw** → voer als naam `happy_herbivore_kiosk` in → klik **Aanmaken**
3. Klik op de nieuwe database → tabblad **Importeren**
4. Kies `sql/kiosk.sql` → klik **Uitvoeren**

### Stap 3 — Database verbinding instellen

Open `config/database.php` en pas aan waar nodig:

```php
define('DB_HOST', '127.0.0.1');  // Niet 'localhost' op Mac!
define('DB_PORT', 8889);          // MAMP gebruikt poort 8889
define('DB_USER', 'root');
define('DB_PASS', 'root');        // MAMP standaard
define('DB_NAAM', 'happy_herbivore_kiosk');
```

> **Let op voor Mac/MAMP gebruikers:** gebruik altijd `127.0.0.1` in plaats van `localhost`.
> `localhost` probeert een Unix socket te gebruiken die MAMP op een andere plek zet.

### Stap 4 — Lettertype installeren

Plaats het Renos-Rough lettertype in `assets/fonts/`:

```
assets/fonts/renos-rough.ttf
```

Het systeem valt automatisch terug op Noto Sans als het bestand ontbreekt.

### Stap 5 — Openen in browser

```
http://localhost/happy-herbivore-kiosk/
```

Of voor MAMP:
```
http://localhost:8888/happy-herbivore-kiosk/
```

---

## Mappenstructuur

```
happy-herbivore-kiosk/
│
├── index.php               ← Idle scherm (startpunt)
├── start.php               ← Taal + besteltype
├── menu.php                ← Menukaart
├── product.php             ← Productdetail + dips
├── overzicht.php           ← Besteloverzicht
├── betaal.php              ← Betaalmethode
├── bon.php                 ← Bevestiging + ophaalcode
│
├── config/
│   └── database.php        ← DB-verbinding (pas hier aan)
│
├── includes/
│   ├── init.php            ← Sessie, taal, helpers laden
│   ├── header.php          ← render_header() + iconen + SVG vlaggen
│   ├── translations.php    ← Alle teksten in NL, EN en DE
│   ├── menu_data.php       ← Producten uit DB of hardcoded fallback
│   └── cart_functions.php  ← Winkelwagen via sessie
│
├── api/
│   ├── cart_add.php        ← AJAX: product toevoegen
│   ├── cart_remove.php     ← AJAX: product verwijderen
│   ├── cart_update.php     ← AJAX: aantal wijzigen
│   └── menu.php            ← JSON endpoint voor producten
│
├── assets/
│   ├── css/
│   │   ├── style.css       ← Volledig ontwerp (merkthema)
│   │   └── start.css       ← Specifieke stijlen voor startscherm
│   ├── js/
│   │   └── app.js          ← Alle kiosk-interactielogica
│   ├── fonts/
│   │   └── renos-rough.ttf ← Plaatsen hier (zie stap 4)
│   ├── images/
│   │   ├── logo_complete.png
│   │   ├── logo_dino.png
│   │   ├── logo_text.png
│   │   └── products/       ← product_1.png t/m product_25.png
│   └── video/
│       └── idle.mp4        ← Achtergrondvideo idle scherm
│
└── sql/
    └── kiosk.sql           ← Volledige database export
```

---

## Database tabellen

| Tabel | Omschrijving |
|---|---|
| `producten` | Alle 25 menu-items met meertalige naam/beschrijving, prijs, kcal, categorie |
| `bestellingen` | Elke geplaatste bestelling met besteltype, taal, totaal, betaalmethode en ophaalcode |
| `bestelling_regels` | Individuele regels per bestelling (product, aantal, prijs per stuk) |

### Productcategorieën
`ontbijt` · `bowls` · `handhelds` · `sides` · `dips` · `dranken`

---

## Productafbeeldingen toevoegen

Afbeeldingen worden automatisch gekoppeld op basis van product-ID:

```
assets/images/products/product_1.png    ← Morning Boost Açaí Bowl
assets/images/products/product_2.png    ← The Garden Breakfast Wrap
...
assets/images/products/product_25.png   ← Citrus Cooler
```

Ondersteunde formaten: `.png`, `.webp`, `.jpg`

Als een afbeelding ontbreekt toont het systeem automatisch een gekleurde categorie-placeholder.

---

## Merkthema

| Element | Waarde |
|---|---|
| Oranje (primair) | `#ff7520` |
| Licht oranje | `#ffb181` |
| Groen (accent) | `#8cd003` |
| Licht groen | `#deff78` |
| Donkerblauw (achtergrond) | `#053631` |
| Heading font | Renos-Rough (fallback: Noto Sans) |
| Body font | Noto Sans (Google Fonts) |

---

## Talen

Het systeem ondersteunt 3 talen. De taal is op elke pagina te wisselen via de vlaggen rechtsboven. De actieve taal wordt opgeslagen in de sessie.

| Code | Taal |
|---|---|
| `nl` | Nederlands (standaard) |
| `en` | English |
| `de` | Deutsch |

Vertalingen beheren: `includes/translations.php`

---

## Producten beheren

### Via de database
Voeg producten toe, bewerk of verwijder ze via phpMyAdmin in de tabel `producten`. Zet `actief = 0` om een product tijdelijk te verbergen zonder het te verwijderen.

### Zonder database (fallback)
Als de database niet beschikbaar is laadt het systeem automatisch de hardcoded menudata uit `includes/menu_data.php`. Bestellingen worden dan niet opgeslagen maar de kiosk blijft volledig werken.

---

## Bekende aandachtspunten

- **Betaalterminal:** de pinbetaling simuleert een 3 seconden wachttijd. Koppel een echte betaalterminal via `api/` aan de `bon.php` redirect.
- **Idle timer:** het systeem keert na 90 seconden inactiviteit automatisch terug naar het idle scherm. Dit is instelbaar in `assets/js/app.js` (`IDLE_TIMEOUT_MS`).
- **Kiosk modus:** voor productiegebruik de browser in kiosk modus draaien (`--kiosk` flag in Chrome/Chromium) zodat de adresbalk verborgen is.

---

## Technische stack

| Onderdeel | Technologie |
|---|---|
| Backend | PHP 8+ |
| Database | MySQL via MySQLi |
| Frontend | HTML5, CSS3, Vanilla JS |
| Fonts | Renos-Rough (TTF) + Noto Sans (Google Fonts) |
| Sessie | PHP native sessions |
| Afbeeldingen | PNG / WebP |
| Video | MP4 (H.264) |

---

*Happy Herbivore — Healthy in a hurry 🦕*
