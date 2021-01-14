<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database_ph.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->id_period);

$res = [];
$i = 0;

$sql = "SELECT * FROM payroll_values WHERE idperiod = $id_period;";

if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idpayroll_values'] = $row['idpayroll_values'];
        $res[$i]['id_employee'] = $row['idemployee'];
        $res[$i]['id_period'] = $row['idperiod'];
        $res[$i]['supervisor'] = $row['ot_regular'];
        $res[$i]['absences'] = $row['absences'];
        $res[$i]['discount'] = $row['discounted_hours'];
        $res[$i]['night_hours'] = $row['night_hours'];
        $res[$i]['ot_regular'] = $row['ot_regular'];
        $res[$i]['ot_rdot'] = $row['ot_rdot'];
        $res[$i]['ot_holiday'] = $row['ot_holiday'];
        $res[$i]['holiday_regular'] = $row['holiday_regular'];
        $res[$i]['holiday_special'] = $row['holiday_special'];
        $i = $i + 1;
    };
    echo(json_encode($res));
}
?>