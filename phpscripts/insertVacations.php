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
$action = ($request->action);
$count = ($request->count);
$took_date = ($request->took_date);

$sql1 = "SELECT `idemployees` FROM `employees` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` WHERE `idprofiles` = $id_employee";

if($r = mysqli_query($con, $sql1)){
    $re = mysqli_fetch_assoc($r);
    $id_employee = $re['idemployees'];

    $sql = "INSERT INTO `hr_processes` (`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`) VALUES (null, '$id_user', '$id_employee', '$id_type', '$id_department', '$date', '$notes', '$status');";

    if(mysqli_query($con, $sql)){
        $idhr_process = mysqli_insert_id($con);
        $sql2 = "INSERT INTO `vacations` (`idvacations`, `id_process`, `action`, `count`, `date`) VALUES (null, '$idhr_process', '$action', '$count', '$took_date');";
        if(mysqli_query($con, $sql2)){
            http_response_code(200);
        }else{
          echo(json_encode(mysqli_error($con)));
            http_response_code(404);
        }
    }
}
?>
