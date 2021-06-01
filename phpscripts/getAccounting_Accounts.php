<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reportAccountingPolicy.csv" . '"');

require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$i = 0;

$sql = "SELECT DISTINCT idaccounting_accounts, external_id, name, clasif FROM accounting_accounts;";
if($result = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($result)) {
    $return[$i]['idaccounting_accounts'] = $row['idaccounting_accounts'];
    $return[$i]['external_id'] = $row['external_id'];
    $return[$i]['name'] = $row['name'];
    $return[$i]['clasif'] = $row['clasif'];
    $i++;
  }
    echo(json_encode($return));
}else{
  http_response_code(400);
  echo($con->error);
  echo("<br>");
  echo($sql);
}
?>