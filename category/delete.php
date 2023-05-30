<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../object/category.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$category = new Category($db);

// get product id
$data = json_decode(file_get_contents("php://input"));

// set product id to be deleted
$category->id = $data->id;

$arr = getAllID();
$check = false;
for($i = 0; $i < count($arr); $i++){
    if($category->id == $arr[$i]){
        // tell the user
        $check = true;
        break;
    }
}

// delete the product
if($category->delete()){

    // set response code - 200 ok
    http_response_code(200);

    if($check){
        echo json_encode(array("message" => "Category was deleted."));
    }else{
        echo json_encode(array("message" => "Category not found."));
    }

}

// if unable to delete the product
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete category."));
}

function getAllID(){
    $connection = mysqli_connect('localhost', 'root', '', 'api_db');
    if(!$connection){
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT id FROM categories";
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
