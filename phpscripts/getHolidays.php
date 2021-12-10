<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-Type:application/pdf");
//header('Content-Type: application/pdf');
require 'database.php';

/*$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$idaccount = ($request->idaccount);
*/
$res = [];
$i = 0;

$sql = "SELECT * FROM holidays;";
if($result = mysqli_query($con,$sql)){
  
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['idholidays'] = $row['idholidays'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['type'] = $row['type'];
    $res[$i]['id_account'] = $row['id_account'];
    $res[$i]['date'] = $row['date'];
    $res[$i]['year'] = $row['year'];
    $i++;
  };

  echo json_encode($res);
} else {
  echo(json_encode($sql));
  http_response_code(420);
}

?>