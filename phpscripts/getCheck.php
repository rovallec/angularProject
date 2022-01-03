<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$account = ($request->account);
$check = ($request->check);


$res = [];
$i = 0;

$sql = "SELECT * FROM checks WHERE bankAccount = '$account' AND document = '$check;'";

if ($result = mysqli_query($con, $sql)) {
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['idchecks'] = $row['idchecks'];
    $res[$i]['place'] = $row['place'];
    $res[$i]['date'] = $row['date'];
    $res[$i]['value'] = $row['value'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['description'] = $row['description'];
    $res[$i]['negotiable'] = $row['negotiable'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['client_id'] = $row['client_id'];
    $res[$i]['id_account'] = $row['id_account'];
    $res[$i]['document'] = $row['document'];
    $res[$i]['bankAccount'] = $row['bankAccount'];
    $res[$i]['printDetail'] = $row['printDetail'];
    $res[$i]['user_create'] = $row['user_create'];
    $res[$i]['creation_date'] = $row['creation_date'];
    $i++;
  };
  echo json_encode($res);
} else {
  echo(json_encode(mysqli_error($con). "<br>" . $sql));
  http_response_code(404);
}

?>