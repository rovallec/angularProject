<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idclauses_templates = $request->idclauses_templates;

$sql = "DELETE FROM clauses_templates WHERE idclauses_templates = $idclauses_templates;";

if(mysqli_query($con,$sql)){
  http_response_code(200);
} else {
  http_response_code(400);
  echo(json_encode($sql));
}
?>