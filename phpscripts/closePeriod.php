<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idperiods);
$start = ($request->start);
$end = ($request->end);
$next_start = '';
$next_end = '';
$days_add = 0;

if((parse_str(explode("-", $end)[2)]) == 15){
    if((cal_days_in_month(CAL_GREGORIAN, (parse_str(explode("-", $end)[1])), (parse_str(explode("-", $end)[1])))) >= 31){
        $next_start = strtotime(date("Y-m-d", strtotime($date)) . " +17 days");
    }else{
        if((cal_days_in_month(CAL_GREGORIAN, (parse_str(explode("-", $end)[1])), (parse_str(explode("-", $end)[1])))) < 30){
            $days_add = (cal_days_in_month(CAL_GREGORIAN, (parse_str(explode("-", $end)[1])), (parse_str(explode("-", $end)[1])))) - 14;
            $next_start = strtotime(date("Y-m-d", strtotime($date)) . " + " . $days_add . " days");
        }else(
            $next_start = strtotime(date("Y-m-d", strtotime($date)) . " +16 days");
        )
    }
}else{
    $next_start = strtotime(date("Y-m-d", strtotime($date)) . " +15 days");
}

if((parse_str(explode("-", $next_start)[2)]) == 15){
    if((cal_days_in_month(CAL_GREGORIAN, (parse_str(explode("-", $next_start)[1])), (parse_str(explode("-", $next_start)[1])))) >= 31){
        $next_end = strtotime(date("Y-m-d", strtotime($date)) . " +17 days");
    }else{
        if((cal_days_in_month(CAL_GREGORIAN, (parse_str(explode("-", $next_start)[1])), (parse_str(explode("-", $next_start)[1])))) < 30){
            $days_add = (cal_days_in_month(CAL_GREGORIAN, (parse_str(explode("-", $next_start)[1])), (parse_str(explode("-", $next_start)[1])))) - 14;
            $next_end = strtotime(date("Y-m-d", strtotime($date)) . " + " . $days_add . " days");
        }else(
            $next_end = strtotime(date("Y-m-d", strtotime($date)) . " +16 days");
        )
    }
}else{
    $next_end = strtotime(date("Y-m-d", strtotime($date)) . " +15 days");
}


$sql = "UPDATE `periods` SET `status` = 0 WHERE `idperiods` = $id";
$sql1 = "INSERT INTO `periods` (`idperiods`, `start`, `end`, `status`) VALUES (null, '$next_start', '$next_end', '1');"
echo($sql1);
#if(mysqli_query($con,$sql)){
#    echo(mysqli_insert_id($con));
#}
?>