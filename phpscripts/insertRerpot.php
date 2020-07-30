<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idreports = ($request->idreports);
$id_process = ($request->id_process);
$tittle = ($request->tittle);
$description = ($request->description);
$classification = ($request->classification);
$idsolutions = ($request->idsolutions);
$id_report = ($request->id_report);
$s_description = ($request->s_description);
$approved_by = ($request->approved_by);

$sql = "INSERT INTO `reports` (`idreports`, `id_process`, `tittle`, `description`, `clasification`) VALUES (NULL, '$id_process', '$tittle', '$description', '$classification');";
if(mysqli_query($con,$sql)){
    $id_report = mysqli_insert_id($con);
    $sql2 = "INSERT INTO `solutions` (`idsolutions`, `id_report`, `description`, `approved_by`) VALUES (NULL, '$id_report', '$s_description', '$approved_by');";
    if(mysqli_query($con,$sql2)){
        http_response_code(200);
    }else{
        http_response_code(400);
    }
}else{
    http_response_code(400);
}
?>