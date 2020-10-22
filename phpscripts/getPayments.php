<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idperiods = ($request->idperiods);
$start = ($request->start);
$end = ($request->end);
$status = ($request->status);

$return = [];
$i = 0;

$sql = "SELECT * FROM `payments` LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile WHERE `id_period` = $idperiods";

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return[$i]['idpayments'] = $res['idpayments'];
        $return[$i]['id_employee'] = $res['id_employee'];
        $return[$i]['id_paymentmethod'] = $res['id_paymentmethod'];
        $return[$i]['id_period'] = $res['id_period'];
        $return[$i]['credits'] = $res['credits'];
        $return[$i]['debits'] = $res['debits'];
        $return[$i]['employee_name'] = $res['first_name'] . " " . $res['second_name'] . " " . $res['first_lastname'] . " " . $res['second_lastname'];
        $return[$i]['date'] = $res['date'];
        $i++;
    }
    echo(json_encode($return));
}
?>