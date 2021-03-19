<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idperiods);
$start = ($request->start);
$end = ($request->end);

$res = [];
$i = 0;
if($start != 'explicit'){
    $sql = "SELECT * FROM paid_attendances INNER JOIN payroll_values ON payroll_values.idpayroll_values = paid_attendances.id_payroll_value WHERE id_period = $id";
}else{
    $sql = "SELECT * FROM paid_attendances 
            INNER JOIN payroll_values ON payroll_values.idpayroll_values = paid_attendances.id_payroll_value 
            WHERE id_employee = $id AND paid_attendances.date BETWEEN $end"
}a

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['id_payroll_value'] = $row['id_payroll_value'];
        $res[$i]['date'] = $row['date'];
        $res[$i]['scheduled'] = $row['scheduled'];
        $res[$i]['worked'] = $row['worked'];
        $res[$i]['balance'] = $row['balance'];
        $res[$i]['idpaid_attendances'] = $row['idpaid_attendances'];
        $res[$i]['id_employee'] = $row['id_employee'];
        $i++;
    }
    echo(json_encode($res));
}
?>