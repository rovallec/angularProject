<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_clause = $request->id_clause;
$id_template = $request->id_template;
$tag = $request->tag;

$sql = "insert into clauses_templates (idclauses_templates,id_clause,id_template,tag) values (null,$id_clause,$id_template,'$tag');";

if(mysqli_query($con,$sql)){
  echo(json_encode('Data saved successfully.'));
  http_response_code(200);
} else {
  echo(json_encode($sql));
  http_response_code(400);
}
?>