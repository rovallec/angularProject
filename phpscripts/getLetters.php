<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `letters` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idletters'] = $row['idletters'];
        $user['id_process'] = $row['id_process'];
        $user['type'] = $row['type'];
        $user['company'] = $row['company'];
        $user['patronal_number'] = $row['patronal_number'];
        $user['emition_date'] = $row['emition_date'];
        $user['language'] = $row['language'];
        $user['position'] = $row['position'];
        $user['department'] = $row['department'];
        $user['base_salary'] = $row['base_salary'];
        $user['productivity_salary'] = $row['productivity_salary'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
