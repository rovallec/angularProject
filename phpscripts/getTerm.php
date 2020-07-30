<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `terminations` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['id_process'] = $row['id_process'];
        $user['motive'] = $row['motive'];
        $user['kind'] = $row['kind'];
        $user['reason'] = $row['reason'];
        $user['rehireable'] = $row['rehireable'];
        $user['nearsol_experience'] = $row['nearsol_experience'];
        $user['valid_from'] = $row['valid_from'];
        $user['comments'] = $row['comments'];
        $user['insurance_notification'] = $row['insurance_notification'];
        $user['access_card'] = $row['access_card'];
        $user['headsets'] = $row['headsets'];
        $user['bank_check'] = $row['bank_check'];
        $user['period_to_pay'] = $row['period_to_pay'];
        $user['supervisor_experience'] = $row['supervisor_experience'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
