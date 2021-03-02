<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idperiods);

$res = [];
$i = 0;

$sql = "SELECT * FROM payroll_values WHERE id_period = $id ORDER BY idpayroll_values";
if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idpayroll_values'] = $row['idpayroll_values'];
        $res[$i]['id_employee'] = $row['id_employee'];
        $res[$i]['id_reporter'] = $row['id_reporter'];
        $res[$i]['id_account'] = $row['id_account'];
        $res[$i]['id_period'] = $row['id_period'];
        $res[$i]['id_payment'] = $row['id_payment'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['discounted_days'] = $row['discounted_days'];
        $res[$i]['seventh'] = $row['seventh'];
        $res[$i]['discounted_hours'] = $row['discounted_hours'];
        $res[$i]['ot_hours'] = $row['ot_hours'];
        $res[$i]['holidays_hours'] = $row['holidays_hours'];
        $res[$i]['performance_bonus'] = $row['performance_bonus'];
        $res[$i]['treasure_hunt'] = $row['treasure_hunt'];
        $i++;
    }
    echo(json_encode($res));
}
?>