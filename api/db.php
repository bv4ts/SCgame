<?php
// api/db.php

// CORS headers - ضروري للاستضافة
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

$host = 'sql313.infinityfree.com';
$user = 'if0_40288418';
$pass = '1Ja1W32ftgZE';
$db   = 'if0_40288418_sc_game';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connection failed']);
  exit;
}
$mysqli->set_charset('utf8mb4');
