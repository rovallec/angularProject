<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "Select * from clauses_templates;";

if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    
    $res[$i]['idclauses_templates'] = $row['idclauses_templates'];
    $res[$i]['id_clause'] = $row['id_clause'];
    $res[$i]['id_template'] = $row['id_template'];
    $res[$i]['ordernum'] = $row['ordernum'];
    $res[$i]['tag'] = $row['tag'];
    $i++;
  }
  echo(json_encode($res));
}
?>