<?php
$host = 'localhost';
$user = 'root';          // Your DB username
$pass = '';              // Your DB password
$dbname = 'fitzone_db';  // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
