<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_asset = $request->idassets;
$id_employee = $request->idemployees;
$id_type = $request->movement;
$id_user = $request->user_name;
$date = $request->date;
$notes = $request->notes;
$status = $request->movement_status;

$sql1 = "INSERT INTO `minearsol`.`asset_movements` (`id_asset`, `id_employee`, `id_user`, `id_type`, `date`, `notes`, `status`) VALUES ($id_asset, $id_employee, $id_user, $id_type, '$date', '$notes', '$status')";
if($result = mysqli_query($con, $sql1)){
    echo(json_encode('1'));
}else{
    echo(json_encode(mysqli_error($con)));
}
?>