<?php
$conn = mysqli_connect("localhost", "root", "", "api_db");

$filename = "products.csv";
$fp = fopen('php://output', 'w');

$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='api_db' AND TABLE_NAME='products'";
$result = mysqli_query($conn,$query);
while ($row = mysqli_fetch_row($result)) {
    $header[] = $row[0];
}

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_row($result)) {
    fputcsv($fp, $row);
}
exit;
