<?php
// geeft de producten terug als json
// dit gebruik ik als ik producten via javascript wil ophalen
require_once '../includes/init.php';
require_once '../includes/menu_data.php';

header('Content-Type: application/json; charset=utf-8');

$lang       = $_SESSION['lang'] ?? ($_GET['lang'] ?? 'nl');
$producten  = menu_ophalen($lang);

echo json_encode([
    'succes'    => true,
    'producten' => $producten,
    'lang'      => $lang,
]);
