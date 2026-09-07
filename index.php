<?php

$hostname = getenv('DB_HOST') ?: 'uzgoah.stackhero-network.com';
$port = (int)(getenv('DB_PORT') ?: 7406);
$user = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'root';

$mysqli = mysqli_init();
$mysqliConnected = $mysqli->real_connect($hostname, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);
if (!$mysqliConnected) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  die(json_encode([
    'success' => false,
    'message' => 'Database connection failed'
  ]));
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'success' => true,
  'message' => 'Connection successful',
  'host' => $mysqli->host_info
]);

$mysqli->close();

?>