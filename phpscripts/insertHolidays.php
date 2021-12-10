<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-Type:application/pdf");
//header('Content-Type: application/pdf');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$name = ($request->name);
$type = ($request->type);
$id_account = ($request->id_account);
$date = ($request->date);
$year = ($request->year);


$sql = "INSERT INTO holidays (idholidays, name, type, id_account, date, year) " .
       "VALUES (null, '$name', $type, $id_account, '$date', $year);";


if (mysqli_query($con, $sql)){
  echo(json_encode('Successfully saved.'));
  http_response_code(200);
} else {
  echo(json_encode($sql));
  http_response_code(420);
}
?>