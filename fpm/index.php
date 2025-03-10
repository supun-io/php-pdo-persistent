<?php

/**
 * ?persistent=1 will use persistent connections
 */
$persistent = boolval($_GET['persistent'] ?? false);

$host = 'mysql'; 
$dbname = 'test';
$username = 'root';
$password = 'mysql';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch data as an associative array
    PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements
    PDO::ATTR_PERSISTENT         => $persistent, // Use persistent connections
];

$pdo = new PDO($dsn, $username, $password, $options);

$statement = $pdo->query('SELECT * FROM users');
$users = $statement->fetchAll();

echo 'PDO persistent connection: ' . ($persistent ? 'enabled' : 'disabled') . '<br><br>';

foreach ($users as $user) {
    $name = $user['name'];
    $email = $user['email'];

    echo "$name ($email) <br>"; 
}

usleep(300000); // Sleep for 0.3 seconds