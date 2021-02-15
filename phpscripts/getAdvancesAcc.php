<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idemployees);

$user = [];

$sql = "SELECT * FROM advances INNER JOIN hr_processes ON hr_processes.idhr_processes = advances.id_process INNER JOIN users ON users.idUser = hr_processes.id_user WHERE id_employee = $id;";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idadvances'] = $row['idadvances'];
        $user[$i]['id_process'] = $row['id_process'];
        $user[$i]['type'] = $row['type'];
        $user[$i]['description'] = $row['description'];
        $user[$i]['classification'] = $row['classification'];
        $user[$i]['amount'] = $row['amount'];
        $user[$i]['idhr_processes'] = $row['idhr_processes'];
        $user[$i]['id_employee'] = $row['id_employee'];
        $user[$i]['date'] = $row['date'];
        $user[$i]['notes'] = $row['notes'];
        $user[$i]['status'] = $row['status'];
        $user[$i]['username'] = $row['username'];
        $user[$i]['user_name'] = $row['user_name'];
        $i;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
