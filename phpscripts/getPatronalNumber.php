<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';

$res = [];
$i = 0;

$sql = "SELECT * FROM patronal p;";
if ($request = mysqli_query($con,$sql)) {
  while($row = mysqli_fetch_assoc($request)){
    $res[$i]['idpatronals'] = $row['idpatronals'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['patronal_number'] = $row['patronal_number'];
    $res[$i]['nit_patrono'] = $row['nit_patrono'];
    $i++;
  }
  echo(json_encode($res));
} else {
  echo(json_encode($sql));
}

?>