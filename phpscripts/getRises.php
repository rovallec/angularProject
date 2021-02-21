<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `rises` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['id_process'] = $row['id_process'];
        $user['new_position'] = $row['new_position'];
        $user['old_position'] = $row['old_position'];
        $user['new_salary'] = $row['new_salary'];
        $user['old_salary'] = $row['old_salary'];
        $user['approved_by'] = $row['approved_by'];
        $user['approved_date'] = $row['approved_date'];
        $user['effective_date'] = $row['effective_date'];
        $user['trial_start'] = $row['trial_start'];
        $user['trial_end'] = $row['trial_end'];
        $user['notes'] = $row['notes'];
        $user['status'] = $row['status'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
