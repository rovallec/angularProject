<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$name = ($request->name);
$nearsol_id = ($request->nearsol_id);
$amount = ($request->amount);
$id_period = ($request->id_period);

$result = [];

$sql = "SELECT * FROM `approved_ot` WHERE `id_employee` = $id_employee AND `id_period` = $id_period;";
if($result = mysqli_query($con,$sql)){
    $row = mysql_fetch_assoc($result);
    $result['idapproved_ot'] = $row[0]['idapproved_ot'];
    $result['id_employee'] = $row[0]['id_emloyee'];
    $result['id_period'] = $row[0]['id_period'];
    $result['amount'] = $row[0]['amount'];
    echo(json_encode($result));
}else{
    http_response_code(400);
}
?>