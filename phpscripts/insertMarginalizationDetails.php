<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

    //Employees
    $idemployees = ($request->idemployees);
    //Master
    $id_user = ($request->id_user);
    $approve_by = ($request->approve_by);
    $date = ($request->date);
    $type = ($request->type);
    //Details
    $id_attendance = ($request->id_attendance);
    $id_marginalization = ($request->id_marginalization);
    $before = ($request->before);
    $after = ($request->after);
    $value = ($request->value);

$sql = "INSERT INTO `minearsol`.`marginalization_details` (`idmarginalization_details`, `id_marginalization`, `id_attendance`, `before`, `after`, `value`) VALUES (NULL, '$id_marginalization', '$id_attendance', '$before', '$after', '$value');";


if($result = mysqli_query($con, $sql))
{
		http_response_code(200);
}else{
	http_response_code(404);
}
?>