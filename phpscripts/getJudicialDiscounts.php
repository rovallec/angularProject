<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$user = [];

$sql = "SELECT * FROM `judicials` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `judicials`.`id_process` WHERE `max` > `current` AND `id_employee` = $id;";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idjudicials'] = $row['idjudicials'];
        $user['id_process'] = $row['id_process'];
        $user['amount'] = $row['amount'];
        $user['current'] = $row['current'];
        $user['max'] = $row['max'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
