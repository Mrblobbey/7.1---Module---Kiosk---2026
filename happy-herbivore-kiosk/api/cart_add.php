<?php
// dit endpoint wordt vanuit javascript aangeroepen als iemand een product toevoegt
// het voegt het product toe aan de sessie winkelmand
require_once '../includes/init.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['succes' => false, 'bericht' => 'Alleen POST toegestaan']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);

if (!$body || !isset($body['product_id'])) {
    echo json_encode(['succes' => false, 'bericht' => 'Ongeldige data']);
    exit;
}

$product_id = (int)   ($body['product_id'] ?? 0);
$naam       = (string)($body['naam']       ?? '');
$prijs      = (float) ($body['prijs']      ?? 0.0);
$kcal       = (int)   ($body['kcal']       ?? 0);
$categorie  = (string)($body['categorie']  ?? 'overig');
$aantal     = max(1, min(10, (int)($body['aantal'] ?? 1)));
$dips       = (array) ($body['dips']       ?? []);

cart_toevoegen($product_id, $naam, $prijs, $kcal, $categorie, $dips, $aantal);

echo json_encode([
    'succes'       => true,
    'cart_aantal'  => cart_aantal_items(),
    'cart_totaal'  => prijs_formaat(cart_totaal(), $_SESSION['lang'] ?? 'nl'),
]);
