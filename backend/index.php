<?php
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
	'success' => true,
	'message' => 'Backend is running',
	'endpoints' => [
		'departments' => 'api/department/get.php'
	]
]);
?>