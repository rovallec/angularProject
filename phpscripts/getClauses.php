<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "Select * from clauses;";

if ($request = mysqli_query($con,$sql)) {
  while($row = mysqli_fetch_assoc($request)){
    $res[$i]['idclauses'] = $row['idclauses'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['description'] = $row['description'];
    $res[$i]['selected'] = 'false'; // Valor por default;
    $i++;
  }
  echo(json_encode($res));
} else {
  echo(json_encode($sql));
}
?>