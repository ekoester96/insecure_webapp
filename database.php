<?php
$host = 'localhost';
$dbname = 'insecure_app_db';
$username = 'php_user';
$password = 'password123'; // The password you set in the SQL commands

try {
    // Establish the connection to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // If connection fails, stop the script and show an error
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
