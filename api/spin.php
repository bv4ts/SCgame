<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

require_once __DIR__.'/db.php';
$cid = (int)($_GET['category_id'] ?? 0);
if (!$cid) { http_response_code(400); echo json_encode(['error'=>'category_id required']); exit; }
$res = $mysqli->query("SELECT id, body FROM questions WHERE is_active=1 AND category_id=$cid ORDER BY RAND() LIMIT 1");
$q = $res->fetch_assoc();
if (!$q) { echo json_encode(['id'=>null, 'body'=>'لا توجد أسئلة لهذا القسم حتى الآن']); exit; }
echo json_encode($q);
