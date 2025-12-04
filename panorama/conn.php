<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ua_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);


$sql = "SELECT * FROM ua_informatie ORDER BY id ASC";

$result = $conn->query($sql);
?>
