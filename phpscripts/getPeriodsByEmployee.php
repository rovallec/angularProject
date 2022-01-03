<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$i = 0;
$return = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);


$sql = "SELECT DISTINCT p.* FROM periods p 
        INNER JOIN payments p2 on (p.idperiods = p2.id_period)
        WHERE p.type_period = 0 
        AND p2.id_employee = $id
        ORDER BY idperiods DESC;";

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