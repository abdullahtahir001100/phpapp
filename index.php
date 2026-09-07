<?php

$hostname = 'uzgoah.stackhero-network.com';
$port = '7406';
$user = 'root';
$password = 'zukBywBtRPTqfrwFTTqv2lPYG1cjKaPn';
$database = 'root'; // While this example uses the "root" database, it is a good idea to create a separate database and user for your application via phpMyAdmin.

$mysqli = mysqli_init();
$mysqliConnected = $mysqli->real_connect($hostname, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);
if (!$mysqliConnected) {
  die("Connection Error: " . $mysqli->connect_error);
}

echo 'Connection successful... ' . $mysqli->host_info . "\n";

$mysqli->close();

?>