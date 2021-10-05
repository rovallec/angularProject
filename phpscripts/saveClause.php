<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$name = $request->name;
$description = $request->description;

$sql = "INSERT INTO clauses (idclauses, name, description) VALUES (null, '$name', '$description');";

if(mysqli_query($con,$sql)){
  echo(json_encode('Data saved successfully.'));
  http_response_code(200);
} else {
  echo(json_encode($sql));
  http_response_code(400);
}
?>