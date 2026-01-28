<?php
// Only send headers if running in web server context (not CLI)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "satka_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
