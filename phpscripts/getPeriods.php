<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$i = 0;
$return = [];
$date = date("Y-m-d");

$dt = date('Y-m-d', strtotime($date . " - 10 month"));
$sql = "SELECT * FROM `periods` WHERE `start` BETWEEN '$dt' AND '$date' and type_period = 0;";

if($result = mysqli_query($con, $sql)){
  while($res = mysqli_fetch_assoc($result)){
    $return[$i]['idperiods'] = $res['idperiods'];
    $return[$i]['start'] = $res['start'];
    $return[$i]['end'] = $res['end'];
    $return[$i]['status'] = $res['status'];
    $return[$i]['type_period'] = $res['type_period'];
    $i++;
  }
  echo(json_encode($return));
  http_response_code(200);
}
?>