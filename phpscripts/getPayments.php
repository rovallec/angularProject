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

$sql = "SELECT * FROM `payments` WHERE `id_period` = $idperiods";

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return['idpayments'] = $res['idpayments'];
        $return['id_employee'] = $res['id_employee'];
        $return['id_paymentmethod'] = $res['id_paymentmethod'];
        $return['id_period'] = $res['id_period'];
        $return['credits'] = $res['credits'];
        $return['debits'] = $res['debits'];
        $return['date'] = $res['date'];
        $i++;
    }
    echo(json_encode($return));
}
?>