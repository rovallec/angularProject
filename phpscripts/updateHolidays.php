<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-Type:application/pdf");
//header('Content-Type: application/pdf');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idholidays = ($request->idholidays);
$name = ($request->name);
$type = ($request->type);
$id_account = ($request->id_account);
$date = ($request->date);
$year = ($request->year);

$res = [];
$i = 0;

$sql = "UPDATE holidays SET 
          name = '$name',
          type = $type, 
          id_account = $id_account, 
          date = '$date', 
          year = year
        WHERE idholidays = $idholidays;";


if (mysqli_query($con, $sql)){
  echo(json_encode('Changes updated.'));
  http_response_code(200);
} else {
  echo(json_encode($sql));
  http_response_code(420);
}
?>