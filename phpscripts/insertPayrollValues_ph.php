<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database_ph.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayroll_values = ($request->idpayroll_values);
$id_employee = ($request->id_employee);
$id_period = ($request->id_period);
$client_id = ($request->client_id);
$nearsol_id = ($request->nearsol_id);
$name = ($request->name);
$supervisor = ($request->supervisor);
$absences = ($request->absences);
$discount = ($request->discount);
$night_hours = ($request->night_hours);
$ot_regular = ($request->ot_regular);
$ot_rdot = ($request->ot_rdot);
$ot_holiday = ($request->ot_holiday);
$holiday_regular = ($request->holiday_regular);
$holiday_special = ($request->holiday_special);

$sql = "INSERT INTO payroll_values (idpayroll_values, idperiod, idemployee, absences, discounted_hours, night_hours, ot_regular, ot_rdot, ot_hld, holiday_regular,".
        "holiday_special) VALUES (NULL, $id_period, $id_employee, $absences, $discount, $night_hours, $ot_regular, $ot_rdot, $ot_holiday, $holiday_regular, $holiday_special)";

if(mysqli_query($con, $sql)){
    echo("1");
}else{
    echo("0");
    http_response_code(404);
}
?>