<?php 
#make database connection with pdo

$host = 'localhost';
$dbname = 'airbridge';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}


?>
