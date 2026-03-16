<?php
// hier stel ik de database verbinding in
// op mijn mac met mamp werkt localhost niet, vandaar 127.0.0.1 en poort 8889
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'u531289_happy_herbivore_kiosk');
define('DB_PASS', 'UGSQdxNaeRbAtBe7aATg');
define('DB_NAAM', 'u531289_happy_herbivore_kiosk');

function db_verbinden(): ?mysqli {
    // mysql fouten uitzetten zodat er geen error op het scherm komt
    mysqli_report(MYSQLI_REPORT_OFF);

    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAAM);

    if (!$conn || $conn->connect_errno) {
        return null; // als het niet lukt geeft het gewoon null terug en werkt de rest nog gewoon
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
