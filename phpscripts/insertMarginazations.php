<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

    //Employees
    $idemployees = ($request->idemployees);
    $id_profile = ($request->id_profile);
    $nearsol_id = ($request->nearsol_id);
    $name = ($request->name);
    //Master
    $idmarginalizations = ($request->idmarginalizations);
    $id_user = ($request->id_user);
    $approve_by = ($request->approve_by);
    $date = ($request->date);
    $type = ($request->type);
    //Details
    $idmarginalization_details = ($request->idmarginalization_details);
    $id_attendance = ($request->id_attendance);
    $id_marginalization = ($request->id_marginalization);
    $before = ($request->before);
    $after = ($request->after);
    $value = ($request->value);

$sql = "INSERT INTO `minearsol`.`marginalizations` (`idmarginalizations`, `id_user`, `approved_by`, `date`, `type`) VALUES (NULL,  '$iduser', '$approve_by', '$date', '$type');";


if($result = mysqli_query($con, $sql))
{
	$id_marginalization = mmysql_insert_id($con);
	$sql2 = "INSERT INTO `minearsol`.`marginalization_details` (`idmarginalization_details`, `id_marginalization`, `id_attendance`, `before`, `after`, `value`) VALUES (NULL, '$id_marginalization', '$id_attendance', '$before', '$after', '$value');";
	if($res = mysqli_query($con, $sql2)){
		http_response_code(200);
	}else{
		http_response_code(404);
	}
}else{
	http_response_code(404);
}
?>