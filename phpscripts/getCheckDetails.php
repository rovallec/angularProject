<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//echo(json_encode($request));

$id_check = ($request->id_check);
$id_check = json_decode($request->id_check);
/*


$res = [];
$i = 0;

$sql = "SELECT * FROM checks_details WHERE id_check = $id_check;";

if ($result = mysqli_query($con, $sql)) {
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['id_check'] = $row['id_check'];
    $res[$i]['id_detail'] = $row['id_detail'];
    $res[$i]['id_account'] = $row['id_account'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['id_movement'] = $row['id_movement'];
    $res[$i]['debits'] = $row['debits'];
    $res[$i]['credits'] = $row['credits'];
    $res[$i]['checked'] = $row['checked'];
    $i++;
  };
  echo json_encode($res);
} else {
  echo(json_encode(mysqli_error($con). "<br>" . $sql));
  http_response_code(404);
}
*/
?>