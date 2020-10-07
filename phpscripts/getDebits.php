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

$sql = "select profiles.idprofiles, debits.iddebits, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, debits.type, debits.amount from debits left join payments on payments.idpayments = debits.id_payment left join employees on employees.idemployees = payments.id_employee left join periods on periods.idperiods = payments.id_period left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile where id_employee = $id AND idperiods = $period LIMIT 20;";

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['iddeductions'] = $row['iddebits'];
        $res[$i]['idemployees'] = $row['idprofiles'];
        $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['type'] = 'DEBIT';
        $res[$i]['reason'] = $row['type'];
        $res[$i]['amount'] = number_format(((float)$row['amount']), 2);
        $i++;
    }
    echo(json_encode($res));
}
?>