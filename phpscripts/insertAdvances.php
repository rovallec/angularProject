<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_process);
$type = ($request->type);
$description = ($request->description);
$classification = ($request->classification);

$sql = "INSERT INTO `advances` (`idadvances`, `id_process`, `type`, `description`, `classification`) VALUES (NULL, '$id_process', '$type', '$description', '$classification');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>