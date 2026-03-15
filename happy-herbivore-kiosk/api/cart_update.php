<?php
// dit endpoint past het aantal van een product aan
// wordt vanuit javascript aangeroepen
require_once '../includes/init.php';
header('Content-Type: application/json; charset=utf-8');

$body    = json_decode(file_get_contents('php://input'), true);
$cart_id = $body['cart_id'] ?? '';
$aantal  = (int)($body['aantal'] ?? 0);

if ($cart_id) {
    cart_update_aantal($cart_id, $aantal);
}

echo json_encode([
    'succes'      => true,
    'cart_aantal' => cart_aantal_items(),
    'cart_totaal' => prijs_formaat(cart_totaal(), $_SESSION['lang'] ?? 'nl'),
]);
