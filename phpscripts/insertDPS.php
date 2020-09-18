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
try {
    $day_1 = ($request->day_1);
} catch (\Throwable $th) {
    $day_1 = '';
}

try {
    $day_2 = ($request->day_2);
} catch (\Throwable $th) {
    $day_2 = '';
}

try {
    $day_3 = ($request->day_3);
} catch (\Throwable $th) {
    $day_3 = '';
}

try {
    $day_4 = ($request->day_4);
} catch (\Throwable $th) {
    $day_4 = '';
}

$sql = "UPDATE `hr_processes` SET `status` = '$status' WHERE `idhr_processes` = '$id_process'";
$sql2 = "INSERT INTO `disciplinary_processes` (`iddisciplinary_processes`,`id_request`,`type`,`cathegory`,`dp_grade`,`motive`,`imposition_date`,`legal_foundament`,`consequences`,`observations`) VALUES (null, '$idrequests', '$type', '$cathegory', '$dp_grade', '$motive', '$imposition_date', '$legal_foundament', '$consequences', '$observations');";
if(mysqli_query($con,$sql)){
    if(mysqli_query($con,$sql2)){
        $iddp = mysqli_insert_id($con);
        $sql3 = "INSERT INTO `suspensions` (`idsuspensions`, `id_disciplinary_process`, `day_1`, `day_2`, `day_3`, `day_4`) VALUES (null, '$iddp', '$day_1', '$day_2', '$day_3', '$day_4');";
        if(mysqli_query($con,$sql3)){
            http_response_code(200);
        }else{
            http_response_code(404);
        }
    }
}
?>