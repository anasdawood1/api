<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../object/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$stmt = $product->read();
$num = $stmt->rowCount();

if($num > 0){
    $products_arr = array();
    $products_arr['records'] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $product_item = array("id" => $row['id'], "name" => $row['name'], "description" => $row['description'], "price" => $row['price'], "category_id" => $row['category_id'], "category_name" => $row['category_name']);
        array_push($products_arr['records'], $product_item);
    }

    http_response_code(200);
    echo json_encode($products_arr);
}else{
    http_response_code(404);
    echo json_encode(array("messege " => "No product found"));
}