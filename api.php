<?php
  require 'db.php';

  header("Content-Type: application/json");

  $request_method = $_SERVER['REQUEST_METHOD'];
  $path_info = $_SERVER['PATH_INFO'] ?? '/';

  $path = explode('/', trim($path_info, '/'));
  $resource = $path[1] ?? null;

  if($resource !== 'products') {
    response(404, "Resource Not Found");
    exit;
  }

    switch ($request_method) {

      case 'GET':
      if($id) {

        getProductById($pdo, $id);
      } else {

      getProducts($pdo);
      }
        break;

      case 'POST':
          createProduct($pdo);
        break;
      
      case 'PUT':
        if ($id) updateProduct($pdo, $id);
        break;

      case 'DELETE';
      if($id) deleteProduct($pdo, $id);
      break;
      
      default:
      response(405, "Method Not Allowed");
      break;
    }

    function getProducts($pdo){

    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    response(200, "Success", $products);
  
  }
  function getProductById($pdo, $id){
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product) {
      respones(200, "Success", $product);
    } else {
      response(404, "Products Not Found");
    }
  }

  function createProduct($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
  if(!isset($input['name']) || !isset($input['price'])){
  response(400, "Invalid Input");
  }

  
  $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    if($stmt->execute([$input['name'], $input['price'] ,$id])){
      response(201, "Product created Sucessfully" );
    }
}

function updateProduct($pdo, $id) {
  $input =json_decode(file_get_contents("php://input"), true);
  $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
  if ($stmt->excute([$input['name'], $input['price'], $id])) {
    response(200, "Product updated Successfully");
  }
}

function response($status, $message, $data = null) {
  http_response_code($status);
  $resp = [
    'status' => $status,
    'message' => $message
  ];
  if ($data !== null) {
    $resp['data'] = $data;
  }
  echo json_encode($resp);
  exit;
}
?>