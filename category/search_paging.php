<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../object/category.php';

// utilities
$utilities = new Utilities();

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

// query products
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
$stmt = $category->searchPaging($keywords, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $category_arr=array();
    $category_arr["records"]=array();
    $category_arr["paging"]=array();

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


    // include paging
    $total_rows=$category->count();
    $page_url="{$home_url}category/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $category_arr["paging"]=$paging;

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($category_arr);
}

else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user products does not exist
    echo json_encode(
        array("message" => "No category found.")
    );
}
?>
