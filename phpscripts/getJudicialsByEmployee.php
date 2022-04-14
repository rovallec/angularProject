<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_employee = ($request->id_employee);
$data = array();
$i=0;

$sql = "SELECT j.*, hp.id_user, u.user_name, hp.`time`, hp.notes, hp.status 
        FROM hr_processes hp
        INNER JOIN judicials j on (hp.idhr_processes = j.id_process AND hp.id_type = 17)
        INNER JOIN users u on (hp.id_user = u.idUser)
        WHERE hp.id_employee = $id_employee;";

if($result = mysqli_query($con, $sql)){
  while($row = mysqli_fetch_assoc($result)){
    $data[$i]['idjudicials'] = $row['idjudicials'];
    $data[$i]['id_process'] = $row['id_process'];
    $data[$i]['amount'] = $row['amount'];
    $data[$i]['max'] = $row['max'];
    $data[$i]['current'] = $row['current'];
    $data[$i]['id_user'] = $row['id_user'];
    $data[$i]['user_name'] = $row['user_name'];
    $data[$i]['time'] = $row['time'];
    $data[$i]['notes'] = $row['notes'];
    $data[$i]['status'] = $row['status'];
    $i++;
  };
  echo json_encode($data);
} else {
  echo json_encode($sql);
  http_response_code(404);
}

?>
