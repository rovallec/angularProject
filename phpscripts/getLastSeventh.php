<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->id_period);
$id_employee = ($request->id_employee);

$return = [];

$sql = "SELECT * FROM payments WHERE id_employee = $id_employee AND idpayments = $id_period ORDER BY id_period DESC LIMIT 1;";

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return['idpayments'] = $res['idpayments'];
        $return['id_employee'] = $res['id_employee'];
        $return['id_paymentmethod'] = $res['id_paymentmethod'];
        $return['id_period'] = $res['id_period'];
        $return['credits'] = $res['credits'];
        $return['debits'] = $res['debits'];
        $return['employee_name'] = $res['first_name'] . " " . $res['second_name'] . " " . $res['first_lastname'] . " " . $res['second_lastname'];
        $return['date'] = $res['date'];
        $return['start'] = $res['start'];
        $return['end'] = $res['end'];
        $return['last_seventh'] = $res['last_seventh'];
    }
    echo(json_encode($return));
}
?>