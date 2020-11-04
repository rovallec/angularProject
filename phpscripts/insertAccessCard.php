<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idcard_assignations = ($request->idcard_assignations);
$id_employee = ($request->id_employee);
$id_process = ($request->id_process);
$code = ($request->code);
$status = ($request->status);
//processes
$idinternal_process = ($request->idinternal_process);
$id_user = ($request->id_user);
$proc_name = ($request->proc_name);
$date = ($request->date);
$proc_status = ($request->proc_status);
$notes = ($request->notes);

$sql = "INSERT INTO `minearsol`.`internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL);";
if(mysqli_query($con, $sql)){
    http_response_code(200);
}else{
    http_response_code(404);
}
?>