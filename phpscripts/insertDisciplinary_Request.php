<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_user = ($request->id_user);
$id_employee = ($request->id_employee);
$id_type = ($request->id_type);
$id_department = ($request->id_department);
$date = ($request->date);
$notes = ($request->notes);
$status = ($request->status);
//Request
$idrequests = ($request->idrequests);
$requested_by = ($request->requested_by);
$reason = ($request->reason);
$description = ($request->description);
$resolution = ($request->resolution);
$proceed = ($request->proceed);

$sql = "INSERT INTO `hr_processes`(`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`) VALUES (null, '$id_user', '$id_employee', '$id_type', '$id_department', '$date', '$notes', '$status')";

if(mysqli_query($con,$sql)){
    $id_process = mysqli_insert_id($con);
    $sql2 = "INSERT INTO `disciplinary_requests` (`iddisciplinary_requests`,`id_process`, `requested_by`, `reason`, `description`, `resolution`, `proceed`) VALUES (null, '$id_process', '$requested_by', '$reason', '$description', '$resolution', '$proceed')";
    if(mysqli_query($con, $sql2)){
        http_response_code(200);
    }else{
        http_response_code(404);
    }
}
}
?>