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
        $user['idadvances'] = $row['idadvances'];
        $user['id_process'] = $row['id_process'];
        $user['type'] = $row['type'];
        $user['description'] = $row['description'];
        $user['classification'] = $row['classification'];
        $user['amount'] = $row['amount'];
        $user['idhr_processes'] = $row['idhr_processes'];
        $user['id_employee'] = $row['id_employee'];
        $user['date'] = $row['date'];
        $user['notes'] = $row['notes'];
        $user['status'] = $row['status'];
        $user['username'] = $row['username'];
        $user['user_name'] = $row['user_name'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
