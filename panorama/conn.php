<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');


$servername = "localhost";
$username = "u240457_ua_database";
$password = "LtUWcXPwKb3YZFCjBPjB";
$dbname = "u240457_ua_database";

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || preg_match('/^192\.168\.2/', $_SERVER['SERVER_ADDR'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ua_database";
} else {
    $servername = "localhost";
    $username = "u240457_ua_database";
    $password = "LtUWcXPwKb3YZFCjBPjB";
    $dbname = "u240457_ua_database";
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);


$sql = "SELECT * FROM ua_informatie ORDER BY id ASC";

$result = $conn->query($sql);
?>