<?php
// in dit bestand staan alle functies voor de winkelwagen
// de winkelwagen bewaar ik in de sessie
function cart_toevoegen(int $product_id, string $naam, float $prijs, int $kcal, string $categorie, array $dips = [], int $aantal = 1): void {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    // als het product al in de wagen zit en zonder dips is, verhoog ik gewoon het aantal
    if (empty($dips)) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $product_id && empty($item['dips'])) {
                $item['aantal'] += $aantal;
                return;
            }
        }
    }

    $_SESSION['cart'][] = [
        'cart_id'    => uniqid('ci_', true),
        'product_id' => $product_id,
        'naam'       => $naam,
        'prijs'      => $prijs,
        'kcal'       => $kcal,
        'categorie'  => $categorie,
        'aantal'     => $aantal,
        'dips'       => $dips,
    ];
}

function cart_verwijder(string $cart_id): void {
    if (!isset($_SESSION['cart'])) return;
    $_SESSION['cart'] = array_values(array_filter(
        $_SESSION['cart'],
        fn($item) => $item['cart_id'] !== $cart_id
    ));
}

function cart_update_aantal(string $cart_id, int $aantal): void {
    if (!isset($_SESSION['cart'])) return;
    if ($aantal <= 0) { cart_verwijder($cart_id); return; }
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['cart_id'] === $cart_id) {
            $item['aantal'] = $aantal;
            return;
        }
    }
}

function cart_totaal(): float {
    if (empty($_SESSION['cart'])) return 0.0;
    $totaal = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        $totaal += $item['prijs'] * $item['aantal'];
        foreach ($item['dips'] as $dip) {
            $totaal += $dip['prijs'] * $item['aantal'];
        }
    }
    return $totaal;
}

function cart_aantal_items(): int {
    if (empty($_SESSION['cart'])) return 0;
    return (int) array_sum(array_column($_SESSION['cart'], 'aantal'));
}

function cart_leegmaken(): void {
    $_SESSION['cart'] = [];
    unset($_SESSION['bestelling_id'], $_SESSION['ophaalcode']);
}

function prijs_formaat(float $bedrag, string $lang = 'nl'): string {
    // nl en de gebruiken een komma als scheiding bij het bedrag
    if ($lang === 'en') {
        return '€' . number_format($bedrag, 2, '.', '');
    }
    return '€' . number_format($bedrag, 2, ',', '');
}
