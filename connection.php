<?php
$host = "localhost";
$username = 'root';
$password = '';
$db = 'foodblog';

try {
	$conn = new PDO("mysql:host=$host;dbname=$db;", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>
