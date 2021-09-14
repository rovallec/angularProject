<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_process);
$notes = $request->notes;
$status = $request->status;

$sql = "UPDATE `hr_processes` SET `status` = '$status', `notes` = '$notes' WHERE `idhr_processes` = $id_process;";

if(mysqli_query($con, $sql)){
    http_response_code(200);
    echo("1");
}else{
    http_response_code(404);
    echo($sql);
}
?>