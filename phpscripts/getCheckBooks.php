<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res = [];
$i = 0;

$sql = "select * from checkbook;";

if ($result = mysqli_query($con, $sql)) {
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['idcheckbook'] = $row['idcheckbook'];
    $res[$i]['account_bank'] = $row['account_bank'];
    $res[$i]['name_bank'] = $row['name_bank'];
    $res[$i]['next_correlative'] = $row['next_correlative'];
    $i++;
  };
  echo json_encode($res);
} else {
  echo(json_encode(mysqli_error($con). "<br>" . $sql));
  http_response_code(404);
}

?>
