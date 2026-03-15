<?php
// dit bestand laad ik op elke pagina als eerste
// het start de sessie en bepaalt welke taal actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// als er nog geen taal is gekozen zet ik hem op nederlands
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'nl';
}

// als er een setlang in de url zit wissel ik de taal
if (isset($_GET['setlang'])) {
    $geldige_talen = ['nl', 'en', 'de'];
    if (in_array($_GET['setlang'], $geldige_talen)) {
        $_SESSION['lang'] = $_GET['setlang'];
    }
    // na het wisselen stuur ik door naar dezelfde pagina maar dan zonder setlang in de url
    $url    = strtok($_SERVER['REQUEST_URI'], '?');
    $params = $_GET;
    unset($params['setlang']);
    $redirect = $url . (!empty($params) ? '?' . http_build_query($params) : '');
    header('Location: ' . $redirect);
    exit;
}

$lang = $_SESSION['lang'];

require_once __DIR__ . '/translations.php';
require_once __DIR__ . '/cart_functions.php';

$t = $TRANSLATIONS[$lang];

// naam van de huidige pagina zodat ik weet waar ik ben
$huidige_pagina = basename($_SERVER['PHP_SELF'], '.php');

// als er nog geen besteltype is gekozen gebruik ik hier eten als standaard
if (!isset($_SESSION['besteltype'])) {
    $_SESSION['besteltype'] = 'hier_eten';
}
$besteltype = $_SESSION['besteltype'];
