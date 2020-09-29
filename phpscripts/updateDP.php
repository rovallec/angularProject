<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_processes);
$status = ($request->status);
$idrequests = ($request->idrequests);
//Disciplinary Process
$type = ($request->type);
$cathegory = ($request->cathegory);
$dp_grade = ($request->dp_grade);
$motive = ($request->motive);
$imposition_date = ($request->imposition_date);
$legal_foundament = ($request->legal_foundament);
$consequences = ($request->consequences);
$observations = ($request->observations);
//Suspensions
$day_1 = ($request->day_1);
$day_2 = ($request->day_2);
$day_3 = ($request->day_3);
$day_4 = ($request->day_4);
if($day_2==''){
    $day_2 = 'null';
}
if($day_3==''){
    $day_3 = 'null';
}
if($day_4==''){
    $day_4 = 'null';
}

$sql = "UPDATE `hr_processes` SET `status` = '$status' WHERE `idhr_processes` = '$id_process'";
if(mysqli_query($con,$sql)){
        http_response_code(200);
    }else{
        http_response_code(404);
        echo($sql4);
    }
}
?>