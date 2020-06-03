<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idwaves);

$user = [];

$sql = "SELECT * FROM `schedules` WHERE `id_wave` = '$id' AND `available` = '1';";

if($result = mysqli_query($con, $sql)){
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idschedules'] = $row['idschedules'];
        $user[$i]['schedule_name'] = $row['schedule_name'];
        $user[$i]['start_time'] = $row['start_time'];
        $user[$i]['end_time'] = $row['end_time'];
        $user[$i]['days_off'] = $row['days_off'];
        $user[$i]['id_wave'] = $row['id_wave'];
        $user[$i]['actual_count'] = $row['actual_count'];
        $user[$i]['max_count'] = $row['max_count'];
        $user[$i]['state'] = $row['available'];
        $user[$i]['show'] = '0';
        $i++;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
