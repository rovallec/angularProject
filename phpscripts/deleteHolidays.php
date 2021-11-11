<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-Type:application/pdf");
//header('Content-Type: application/pdf');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idholidays = ($request->idholidays);

$res = [];
$i = 0;

$sql = "DELETE FROM holidays WHERE idholidays = $idholidays;";


if (mysqli_query($con, $sql)){
  echo(json_encode('Successfully removed.'));
  http_response_code(200);
} else {
  echo(json_encode($sql));
  http_response_code(420);
}
?>