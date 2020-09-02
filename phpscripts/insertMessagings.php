<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idmessagings = ($request->idmessagings);
$id_process = ($request->id_process);
$type = ($request->type);
$notes = ($request->notes);

$sql = "INSERT INTO `messagings` (`idmessagings;`, `id_process`, `type`, `notes`) VALUES (NULL, '$id_process', '$type', '$notes');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>