<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../object/category.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query products
$stmt = $category->search($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $category_arr=array();
    $category_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $category_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );

        array_push($category_arr["records"], $category_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data
    echo json_encode($category_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No category found.")
    );
}
?>
