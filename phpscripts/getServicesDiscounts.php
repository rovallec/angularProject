<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$user = [];
$i = 0;
$date = ($request->date);

if(parse_str(explode("-",$date)[2]) > 1){
    $sql = "SELECT * FROM `services` WHERE (`frecuency` = 'PAYMENT' OR `frecuency` = 'MONTHLY') AND `id_employee` = $id AND `status` = 1;";
}else{
    $sql = "SELECT * FROM `services` WHERE (`frecuency` = 'PAYMENT' OR `frecuency` = 'BIWEEKLY') AND `id_employee` = $id AND `status` = 1;";
}

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idservices'] = $row['idservices'];
        $user[$i]['id_process'] = $row['id_process'];
        $user[$i]['id_employee'] = $row['id_employee'];
        $user[$i]['name'] = $row['name'];
        $user[$i]['amount'] = $row['amount'];
        $user[$i]['max'] = $row['max'];
        $user[$i]['frecuency'] = $row['frecuency'];
        $user[$user]['status'] = $row['status'];
        $user[$user]['current'] = $row['current'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>