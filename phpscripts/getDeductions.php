<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);

$sql = "select profiles.idprofiles, employees.idemployees, debits.iddebits, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, debits.type, debits.amount from debits left join payments on payments.idpayments = debits.id_payment left join employees on employees.idemployees = payments.id_employee left join periods on periods.idperiods = payments.id_period left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile where id_period = $id LIMIT 20;";

$sql2 = "select profiles.idprofiles, employees.idemployees, credits.idcredits, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, credits.type, credits.amount from credits left join payments on payments.idpayments = credits.id_payment left join employees on employees.idemployees = payments.id_employee left join periods on periods.idperiods = payments.id_period left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile where id_period = $id LIMIT 20;";


if($request = mysqli_query($con,$sql)){
    if($request2 = mysqli_query($con, $sql2)){
        while($row = mysqli_fetch_assoc($request)){
            $res[$i]['iddeductions'] = $row['iddebits'];
            $res[$i]['idprofiles'] = $row['idprofiles'];
            $res[$i]['idemployees'] = $row['idemployees'];
            $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
            $res[$i]['type'] = 'DEBIT';
            $res[$i]['reason'] = $row['type'];
            $res[$i]['amount'] = number_format(((float)$row['amount']), 2);
            $i++;
        }
        while($row2 = mysqli_fetch_assoc($request2)){
            $res[$i]['iddeductions'] = $row2['idcredits'];
            $res[$i]['idprofiles'] = $row2['idprofiles'];
            $res[$i]['idemployees'] = $row2['idemployees'];
            $res[$i]['name'] = $row2['first_name'] . " " . $row2['second_name'] . " " . $row2['first_lastname'] . " " . $row2['second_lastname'];
            $res[$i]['type'] = 'CREDIT';
            $res[$i]['reason'] = $row2['type'];
            $res[$i]['amount'] = number_format(((float)$row2['amount']), 2);
            $i++;
        }
        echo(json_encode($res));
    }
}
?>