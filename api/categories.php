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

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

  $res = $mysqli->query("SELECT id, name, color FROM categories WHERE is_active=1 ORDER BY id");
  echo json_encode($res->fetch_all(MYSQLI_ASSOC)); exit;
}

if ($method === 'POST') {
  $action = $_POST['_action'] ?? 'add';
  
  if ($action === 'add') {
    $name = $_POST['name'] ?? '';
    $color = $_POST['color'] ?? '#ff0000';
    if (!$name) { http_response_code(400); echo json_encode(['error'=>'name required']); exit; }
    $stmt = $mysqli->prepare("INSERT INTO categories (name, color) VALUES (?,?)");
    $stmt->bind_param('ss', $name, $color);
    $stmt->execute();
    echo json_encode(['id'=>$stmt->insert_id]); exit;
  }
  
  if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = $_POST['name'] ?? null;
    $color = $_POST['color'] ?? null;
    if (!$id || !$name) { http_response_code(400); echo json_encode(['error'=>'id & name required']); exit; }
    
    $stmt = $mysqli->prepare("UPDATE categories SET name=?, color=? WHERE id=?");
    if (!$stmt) {
      echo json_encode(['error'=>'Prepare failed', 'mysql_error'=>$mysqli->error]); 
      exit;
    }
    
    $stmt->bind_param('ssi', $name, $color, $id);
    $result = $stmt->execute();
    
    if (!$result) {
      echo json_encode(['error'=>'Execute failed', 'mysql_error'=>$stmt->error]); 
      exit;
    }
    
    echo json_encode(['ok'=>1]); exit;
  }
  
  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) { 
      http_response_code(400); 
      echo json_encode(['error'=>'id required']); 
      exit; 
    }
    
    $stmt = $mysqli->prepare("UPDATE categories SET is_active=0 WHERE id=?");
    if (!$stmt) {
      echo json_encode(['error'=>'Prepare failed', 'mysql_error'=>$mysqli->error]); 
      exit;
    }
    
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    
    if (!$result) {
      echo json_encode(['error'=>'Execute failed', 'mysql_error'=>$stmt->error, 'mysqli_errno'=>$mysqli->errno]); 
      exit;
    }
    
    echo json_encode(['ok'=>1, 'affected'=>$stmt->affected_rows, 'id'=>$id]); 
    exit;
  }
}

if ($method === 'PUT') {
  parse_str(file_get_contents('php://input'), $_PUT);
  $id = (int)($_PUT['id'] ?? 0);
  $name = $_PUT['name'] ?? null;
  $color = $_PUT['color'] ?? null;
  if (!$id || !$name) { http_response_code(400); echo json_encode(['error'=>'id & name required']); exit; }
  
  $stmt = $mysqli->prepare("UPDATE categories SET name=?, color=? WHERE id=?");
  if (!$stmt) {
    echo json_encode(['error'=>'Prepare failed', 'mysql_error'=>$mysqli->error]); 
    exit;
  }
  
  $stmt->bind_param('ssi', $name, $color, $id);
  $result = $stmt->execute();
  
  if (!$result) {
    echo json_encode(['error'=>'Execute failed', 'mysql_error'=>$stmt->error]); 
    exit;
  }
  
  echo json_encode(['ok'=>1]); exit;
}

if ($method === 'DELETE') {
  $input = file_get_contents('php://input');
  parse_str($input, $_DEL);
  $id = (int)($_DEL['id'] ?? 0);
  if (!$id) { 
    http_response_code(400); 
    echo json_encode(['error'=>'id required']); 
    exit; 
  }
  
  $stmt = $mysqli->prepare("UPDATE categories SET is_active=0 WHERE id=?");
  if (!$stmt) {
    echo json_encode(['error'=>'Prepare failed', 'mysql_error'=>$mysqli->error]); 
    exit;
  }
  
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();
  
  if (!$result) {
    echo json_encode(['error'=>'Execute failed', 'mysql_error'=>$stmt->error, 'mysqli_errno'=>$mysqli->errno]); 
    exit;
  }
  
  echo json_encode(['ok'=>1, 'affected'=>$stmt->affected_rows, 'id'=>$id]); 
  exit;
}
