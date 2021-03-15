<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$sql2 = "";
$r = [];
$i = 0;
$sql = "INSERT INTO `minearsol`.`payroll_values` (`idpayroll_values`,`id_employee`, `id_reporter`, `id_account`, `id_period`, `id_payment`, `client_id`," .
        " `nearsol_id`, `discounted_days`, `seventh`, `discounted_hours`, `ot_hours`, `holidays_hours`, `performance_bonus`, `treasure_hunt`,`next_seventh`) VALUES";

for ($i=0; $i < (count($request)); $i++) { 
    if($i != 0){
        $sql = $sql . ",";
    }
    //select:
    $id_select_period = $request[$i]->id_period;
    
    //insert:
    $idpayroll_values = $request[$i]->idpayroll_values;
    $id_employee = $request[$i]->id_employee;
    $id_reporter = $request[$i]->id_reporter;
    $id_account = $request[$i]->id_account;
    $id_period = $request[$i]->id_period;
    $id_payment = $request[$i]->id_payment;
    $client_id = $request[$i]->client_id;
    $nearsol_id = $request[$i]->nearsol_id;
    $discounted_days = $request[$i]->discounted_days;
    $seventh = $request[$i]->seventh;
    $discounted_hours = $request[$i]->discounted_hours;
    $ot_hours = $request[$i]->ot_hours;
    $holidays_hours = $request[$i]->holidays_hours;
    $performance_bonus = "0";
    $treasure_hunt = "0";
    $agent_name = $request[$i]->agent_name;
    $account_name = $request[$i]->account_name;
    $agent_status = $request[$i]->agent_status;
    $total_days = $request[$i]->total_days;
    $next_seventh = $request [$i]->next_seventh;
    $sql = $sql . 
           "(NULL,$id_employee, $id_reporter, $id_account, $id_period, $id_payment, '$client_id', '$nearsol_id', $discounted_days, $seventh, $discounted_hours, $ot_hours, $holidays_hours, $performance_bonus, $treasure_hunt, $next_seventh)";
}
if(mysqli_query($con,$sql)){
    echo("1");
}else{
    $str = $sql2 . "|" . mysqli_error($con);
    echo(json_encode($str));
}
?>