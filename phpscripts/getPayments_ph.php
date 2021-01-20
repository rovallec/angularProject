<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database_ph.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idperiods = ($request->idperiods);
$start = ($request->start);
$end = ($request->end);
$status = ($request->status);

$return = [];
$i = 0;

if($start != 'all'){
    if($start == 'explicit'){
            $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile WHERE `id_employee` = $status AND `idperiods` IN ($idperiods);";
    }else{
            $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile WHERE `id_period` = $idperiods;";
        }
}else{
    if($start != "explicit"){
        $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile WHERE `id_employee` = $status;";
    }else if($start == 'explicit'){
        $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile WHERE `id_employee` = $status AND `idperiods` IN ($idperiods);";
    }else if($start == 'explicit_employee'){
        $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile WHERE `id_employee` = $status;";
    }
}

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
        $return[$i]['start'] = $res['start'];
        $return[$i]['end'] = $res['end'];
        $return[$i]['last_seventh'] = $res['last_seventh'];
        $i++;
    }
    echo(json_encode($return));
}
?>