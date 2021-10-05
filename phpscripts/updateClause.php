<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idclauses = $request->idclauses;
$name = $request->name;
$description = $request->description;

$sql = "UPDATE clauses SET 
          name = '$name',
          description = '$description'
        WHERE idclauses = $idclauses;";

if(mysqli_query($con,$sql)){
  echo(json_encode('Data saved successfully.'));
  http_response_code(200);
} else {
  http_response_code(400);
  echo(json_encode($sql));
}
?>