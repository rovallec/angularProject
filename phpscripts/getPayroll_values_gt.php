<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idperiods);

$res = [];
$i = 0;

$sql = "SELECT payroll_values.id_account AS `acc`, employees.*, hires.*, profiles.*, accounts.*, payroll_values.* FROM payroll_values
INNER JOIN employees ON employees.idemployees = payroll_values.id_employee
INNER JOIN hires ON hires.idhires = employees.id_hire
INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
INNER JOIN accounts ON accounts.idaccounts = payroll_values.id_account
WHERE id_period = $id";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idpayroll_values'] = $row['idpayroll_values'];
        $res[$i]['id_employee'] = $row['id_employee'];
        $res[$i]['id_reporter'] = $row['id_reporter'];
        $res[$i]['id_account'] = $row['acc'];
        $res[$i]['id_period'] = $row['id_period'];
        $res[$i]['id_payment'] = $row['id_payment'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['discounted_days'] = $row['discounted_days'];
        $res[$i]['seventh'] = $row['seventh'];
        $res[$i]['discounted_hours'] = $row['discounted_hours'];
        $res[$i]['ot_hours'] = $row['ot_hours'];
        $res[$i]['holidays_hours'] = $row['holidays_hours'];
        $res[$i]['performance_bonus'] = $row['performance_bonus'];
        $res[$i]['treasure_hunt'] = $row['treasure_hunt'];
        $res[$i]['agent_name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['account_name'] = $row['name'];
        $res[$i]['agent_status'] = $row['active'];
        $res[$i]['adj_holidays'] = $row['adj_hours'];
        $res[$i]['adj_ot'] = $row['adj_ot'];
        $res[$i]['adj_hours'] = $row['adj_holidays'];
        $res[$i]['nearsol_bonus'] = $row['nearsol_bonus'];

        adj_holidays:string = null;
        adj_ot:string = null;
        adj_hours:string = null;
        nearsol_bonus:string = null;
        $i++;
    }
    echo(json_encode($res));
}
?>