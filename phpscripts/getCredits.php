<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);
$period = ($request->period);

$sql = "select profiles.idprofiles, credits.idcredits, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, credits.status, credits.type, credits.amount from credits left join payments on payments.idpayments = credits.id_payment left join employees on employees.idemployees = payments.id_employee left join periods on periods.idperiods = payments.id_period left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile where id_employee = $id AND idperiods = $period;";

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['iddebits'] = $row['idcredits'];
        $res[$i]['idpayments'] = $row['idprofiles'];
        $res[$i]['type'] = $row['type'];
        $res[$i]['amount'] = $row['amount'];
        $res[$i]['status'] = $row['status'];
        $i++;
    }
    echo(json_encode($res));
}
?>