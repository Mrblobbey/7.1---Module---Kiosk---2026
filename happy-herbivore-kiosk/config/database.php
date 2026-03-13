
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kiosk";

$conn = new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
die("Database connectie mislukt");
}
?>
