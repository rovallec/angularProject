<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-Type:application/pdf");
//header('Content-Type: application/pdf');
require 'database.php';
/*
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
*/

$id = $_GET['id'];

$sql = "SELECT archivo, name FROM file WHERE id_process = $id LIMIT 0, 1;";
if($result = mysqli_query($con,$sql)){
$row = mysqli_fetch_assoc($result);
  //while($row = mysqli_fetch_assoc($result)){
    $file = $row['archivo'];
    $name = $row['name'];
    
    //$data = str_replace("data:application/pdf;base64,","",$data);
    $data = base64_decode($file);
  //};

  echo $data;
} else {
  echo(json_encode($sql));
  http_response_code(420);
}

?>