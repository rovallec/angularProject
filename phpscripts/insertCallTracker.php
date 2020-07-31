<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idcall_tracks = ($request->idcall_tracks);
$id_process = ($request->id_process);
$type = ($request->type);
$reason = ($request->reason);
$channel = ($request->channel);

$sql = "INSERT INTO `call_tracks` (`idcall_tracks`, `id_process`, `type`, `reason`, `channel`) VALUES (NULL, '$id_process', '$type', '$reason', '$channel');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>