<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata,TRUE);
$notes = ($request['notes']);
$nearsol_id = ($request['nearsol_id']);
$process = ($request['process']);
$wave = ($request['wave']);
$reporter = ($request['reporter']);

$id_role = $process["id_role"];
$id_profile = $process["id_profile"];
$process_name = $process["name"];
$descritpion = $process["descritpion"];
$prc_date = $process["prc_date"];
$status = $process["status"];
$id_user = $process["id_user"];

$id_wave = $wave["idwaves"];

$id_schedule = ($request["id_schedule"]);
$actual_count = ($request["schedule_count"]);
$next_state = ($request["next_state"]);

$reports_to = $reporter["idUser"];
$hires = $wave["hires"] + 1;


if($process_name=="Campaign Assignation"){
    $sql = "INSERT INTO `processes`(`id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES ('$id_role', '$id_profile', '$process_name', '$descritpion', '$prc_date', '$id_user', '$status');";
    if(mysqli_query($con, $sql)){
        $idprocesses = mysqli_insert_id($con);
        $sql2 = "INSERT INTO `process_details`(`id_process`, `name`, `value`) VALUES ('$idprocesses','Notes', '$notes');";
        if(mysqli_query($con, $sql2)){
            $sql3 = "INSERT INTO `hires`(`id_profile`, `id_wave`, `nearsol_id`, `reports_to`, `id_schedule`) VALUES ($id_profile, '$id_wave', '$nearsol_id', '$reports_to', '$id_schedule');";
            if(mysqli_query($con, $sql3)){
                $sql4 = "UPDATE `waves` SET `hires`= '$hires' WHERE `idwaves` = '$id_wave';";
                $sql5 = "UPDATE `schedules` SET `actual_count`= '$actual_count' WHERE `idschedules` = '$id_schedule';";
                $sql6 = "UPDATE `schedules` SET `available` = '$next_state' WHERE `idschedules` = '$id_schedule';";
                if(mysqli_query($con, $sql4)){
                    if(mysqli_query($con, $sql5)){
                        if(mysqli_query($con,$sql6)){
                            echo($idprocesses);
                        }else{
                            http_response_code(404);
                        }    
                    }
                }
            }
        }
    }
}

?>