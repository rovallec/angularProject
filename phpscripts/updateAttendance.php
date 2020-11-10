<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idattendences = ($request->idattendences);
$id_employee = ($request->id_employee);
$nearsol_id = ($request->nearsol_id);
$client_id = ($request->client_id);
$first_name = ($request->first_name);
$second_name = ($request->second_name);
$first_lastname = ($request->first_lastname);
$second_lastname = ($request->second_lastname);
$date = ($request->date);
$scheduled = ($request->scheduled);
$worked_time = ($request->worked_time);
$day_off1 = ($request->day_off1);
$day_off2 = ($request->day_off2);
$status = ($request->status);
$id_wave = ($request->id_wave);
$balance = ($request->balance);


$sql = "UPDATE `attendences` SET `scheduled` = '$scheduled', `worked_time` = '$worked_time' WHERE `idattendences` = $idattendences;";
echo($sql);



if($result = mysqli_query($con, $sql))
{
	http_response_code(200);
}else{
	http_response_code(404);
}
?>