<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "Select * from contract_templates;";

if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    $res[$i]['idtemplates'] = $row['idtemplates'];
    $res[$i]['name'] = $row['name'];
    $i++;
  }
  echo(json_encode($res));
}
?>