<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$i = 0;
$return = [];
$date = date("Y-m-d");

$sql = "SELECT * FROM `periods` WHERE type_period = 0 ORDER BY idperiods;";

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