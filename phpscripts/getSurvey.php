<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `supervisor_surveies` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idsupervisor_survey'] = $row['idsupervisor_surveies'];
        $user['id_process'] = $row['id_process'];
        $user['amount'] = $row['amount'];
        $user['approved_date'] = $row['approved_date'];
        $user['notification_date'] = $row['notification_date'];
        $user['score'] = $row['score'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
