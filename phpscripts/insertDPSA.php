<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_processes)
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
//Audiences
$audience_date = ($request->audience_date);
$time = ($request->time);
$comments = ($request->comments);
$audience_status = ($request->audience_status);
//Suspensions
$start = ($request->start);
$end = ($request->end);

$sql = "UPDATE `hr_processes` SET `status` = '$status' WHERE `idhr_processes` = '$id_process'";
$sql2 = "INSERT INTO `disciplinary_processes` (`iddisciplinary_processes`,`id_request`,`type`,`cathegory`,`dp_grade`,`motive`,`imposition_date`,`legal_foundament`,`consequences`,`observations`) VALUES (null, '$idrequests', '$type', '$cathegory', $dp_grade', '$motive', '$imposition_date', '$legal_foundament', '$consequences', '$observations');";
if(mysqli_query($con,$sql)){
    if(mysqli_query($con,$sql2)){
        $iddp = mysqli_insert_id($con);
        $sql3 = "INSERT INTO `audiences` (`idaudiences`, `id_disciplinary_process`, `date`, `time`, `comments`, `status`) VALUES (null, '$iddp', '$audience_date', '$time', '$comments', '$status');";
        if(mysqli_query($con,$sql3)){
            $sql4 = "INSERT INTO `audiences` (`idaudiences`, `id_disciplinary_process`, `date`, `time`, `comments`, `status`) VALUES (null, '$iddp', '$audience_date', '$time', '$comments', '$status');";
            if(mysqli_query($con,$sql4)){
                http_response_code(200);
            }else{
                http_response_code(404);
            }
        }
    }
}
?>