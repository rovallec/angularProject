<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database_ph.php';
$res = [];
$i = 0;

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);
$period = ($request->period);

if($id != 'all'){
    $sql = "SELECT profiles.idprofiles, credits.idcredits, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, credits.type, 
    credits.amount, hires.nearsol_id, employees.client_id FROM credits
    LEFT JOIN payments ON payments.idpayments = credits.id_payment 
    LEFT JOIN employees ON employees.idemployees = payments.id_employee 
    LEFT JOIN periods ON periods.idperiods = payments.id_period 
    LEFT JOIN hires ON hires.idhires = employees.id_hire 
    LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile where id_employee = $id AND idperiods = $period;";
}else{
    $sql = "SELECT profiles.idprofiles, credits.idcredits, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, credits.type, 
    credits.amount, hires.nearsol_id, employees.client_id FROM credits
    LEFT JOIN payments ON payments.idpayments = credits.id_payment 
    LEFT JOIN employees ON employees.idemployees = payments.id_employee 
    LEFT JOIN periods ON periods.idperiods = payments.id_period 
    LEFT JOIN hires ON hires.idhires = employees.id_hire 
    LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile where idperiods = $period;";
}


if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['iddebits'] = $row['idcredits'];
        $res[$i]['idpayments'] = $row['idprofiles'];
        $res[$i]['type'] = $row['type'];
        $res[$i]['amount'] = $row['amount'];
        $res[$i]['id_user'] = $row['nearsol_id'];
        $res[$i]['notes'] = $row['client_id'];
        $res[$i]['id_process'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $i++;
    }
    echo(json_encode($res));
}
?>