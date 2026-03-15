<?php
// dit endpoint verwijdert een product uit de winkelmand
// wordt vanuit javascript aangeroepen
require_once '../includes/init.php';
header('Content-Type: application/json; charset=utf-8');

$body    = json_decode(file_get_contents('php://input'), true);
$cart_id = $body['cart_id'] ?? '';

if ($cart_id) {
    cart_verwijder($cart_id);
}

echo json_encode(['succes' => true, 'cart_aantal' => cart_aantal_items()]);
