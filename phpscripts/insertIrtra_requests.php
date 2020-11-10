<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idprocess = ($request->idprocess);
$idirtra_requests = ($request->idirtra_requests);
$type = ($request->type);
$spuse_name = ($request->spouse_name);
$spouse_lastname = ($request->spouse_lastname);

$sql = "INSERT INTO `irtra_requests` (`idirtra_requests`, `id_process`, `type`, `spouse_name`, `spouse_lastname`) VALUES (NULL, '$idprocess', '$type', '$spuse_name', '$spouse_lastname');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>