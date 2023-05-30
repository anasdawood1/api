<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/product.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of product to be edited
$product->id = $data->id;

// set product property values
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// update the product
if($product->update()){

    // set response code - 200 ok
    http_response_code(200);

    $arr = getAllID();
    $check = false;
    for($i = 0; $i < count($arr); $i++){
        if($product->id == $arr[$i]){
            // tell the user
            $check = true;
            break;
        }
    }

    if($check){
        // tell the user
        echo json_encode(array("message" => "Product was deleted."));
    }else{
        // tell the user
        echo json_encode(array("message" => "Product not found."));
    }


}

// if unable to update the product, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update product."));
}

function getAllID(){
    $connection = mysqli_connect('localhost', 'root', '', 'api_db');
    if(!$connection){
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT id FROM products";
    $result = mysqli_query($connection, $query);
    global $id_arr;
    if($result){
        if (mysqli_num_rows($result) > 0) {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $id_arr[$i] = $row['id'];
                $i++;
            }
        }
        return $id_arr;
    }
}
?>
