<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idperiods);
$start = ($request->start);
$end = ($request->end);
$days_add = 0;


$date_explode = explode("-", $start);

$next_start = date("Y-m-d", strtotime($end . " +1 days"));


$nextDate_explode = explode("-",$next_start);

if(parse_str(explode("-", $next_start)[2]) == 15){
    if(cal_days_in_month(CAL_GREGORIAN, $nextDate_explode[1], $nextDate_explode[0]) >= 31){
        $next_end = date("Y-m-d", strtotime($next_start . " +17 days"));
    }else{
        if(cal_days_in_month(CAL_GREGORIAN,  $nextDate_explode[1], $nextDate_explode[0]) < 30){
            $days_add = (cal_days_in_month(CAL_GREGORIAN,  $nextDate_explode[1], $nextDate_explode[0])) - 14;
            $next_end = date("Y-m-d", strtotime($next_start . " + " . $days_add . " days"));
        }else{
            $next_end = date("Y-m-d", strtotime($next_start . " +16 days"));
        }
    }
}else{
    $next_end = date("Y-m-d", strtotime($next_start . " +15 days"));
}


$sql = "UPDATE `periods` SET `status` = 0 WHERE `idperiods` = $id";
$sql1 = "INSERT INTO `periods` (`idperiods`, `start`, `end`, `status`) VALUES (null, '$next_start', '$next_end', '1');";
$sql2 = "SELECT * FROM `payment_methods` LEFT JOIN `employees` ON `employees`.`idemployees` = `payment_methods`.`id_employee` WHERE `predeterm` = 1 AND `active` = 1;";
if(mysqli_query($con,$sql)){
    if(mysqli_query($con, $sql1)){
        $id_period = mysqli_insert_id($con);
        if($request = mysqli_query($con, $sql2)){
            while($row = mysqli_fetch_assoc($request)){
                $id_employee = $row['idemployees'];
                $id_paymentmethod = $row['idpayment_methods'];
                $sql3 = "INSERT INTO `payments` (`idpayments`, `id_employee`, `id_paymentmethod`, `id_period`, `credits`, `debits`, `date`) VALUES (null, '$id_employee', '$id_paymentmethod', '$id_period', '0.00', '0.00', null);";
                if(!mysqli_query($con, $sql3)){
                break;
                }
            }
        }
    }
}
?>