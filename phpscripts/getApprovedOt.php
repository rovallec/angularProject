<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$id_period = ($request->id_period);

$res = [];

$sql = "SELECT * FROM `approved_ot` WHERE `id_employee` = $id_employee AND `id_period` = $id_period;";
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $res['idapproved_ot'] = $row['idapproved_ot'];
        $res['id_employee'] = $row['id_employee'];
        $res['id_period'] = $row['id_period'];
        $res['amount'] = $row['amount'];
    };
    echo(json_encode($res));
}else{
    http_response_code(400);
}
?>