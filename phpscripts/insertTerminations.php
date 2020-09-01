<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_process);
$motive = ($request->motive);
$kind = ($request->kind);
$reason = ($request->reason);
$rehireable = ($request->rehireable);
$nearsol_experience = ($request->nearsol_experience);
$supervisor_experience = ($request->supervisor_experience);
$valid_from = ($request->valid_from);
$comments = ($request->comments);
$insurance_notification = ($request->insurance_notification);
$access_card = ($request->access_card);
$headsets = ($request->headsets);
$bank_check = ($request->bank_check);
$period_to_pay = ($request->period_to_pay);

$sql = "INSERT INTO `terminations` (`idterminations`, `id_process`, `motive`, `kind`, `reason`, `rehireable`, `nearsol_experience`, `supervisor_experience`, `comments`, `valid_from`, `access_card`, `headsets`, `bank_check`, `insurance_notification`, `period_to_pay`) VALUES (NULL, '$id_process', '$motive', '$kind', '$reason', '$rehireable', '$nearsol_experience', '$supervisor_experience', '$comments', '$valid_from', '$access_card', '$headsets', '$bank_check', '$insurance_notification', '$period_to_pay');";

if(mysqli_query($con,$sql)){
    $sql2 = "UPDATE `employees` SET `active` = '0';";
    if(mysqli_query($con, $sql2)){
        $sql3 = "UPDATE `profiles` SET `status` = '$rehireable';";
        if(mysqli_query($con, $sql3)){
            http_response_code(200);
        }else{
            http_response_code(400);
        }
    }
}
?>