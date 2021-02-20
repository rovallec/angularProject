<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_user = ($request->id_user);
$id_employee = ($request->id_profile);
$id_type = ($request->idprocesses);
$id_department = '5';
$notes = ($request->descritpion);
$date = ($request->prc_date);
$status = ($request->status);

$sql = "INSERT INTO `minearsol`.`hr_processes` (`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`) VALUES (NULL, '$id_user', '$id_employee', '$id_type', '$id_department', '$date', '$notes', '$status');";
if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
} else
{
    echo($sql);
}
?>