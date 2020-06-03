<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

    $postdata = file_get_contents("php://input");
    
    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);
        $idschedules = ($request->idschedules);
        $schedule_name = ($request->schedule_name);
        $start_time = ($request->start_time);
        $end_time = ($request->end_time);
        $id_wave = ($request->id_wave);
        $actual_count = ($request->actual_count);
        $max_count = ($request->max_count);
        $available = ($request->state);
        $days_off = ($request->days_off);

        $sql = "UPDATE `schedules` SET `schedule_name`='$schedule_name',`start_time`='$start_time',`end_time`='$end_time',`id_wave`='$id_wave',`actual_count`='$actual_count',`max_count`='$max_count',`available`='$available', `days_off`='$days_off' WHERE `idschedules`='$idschedules'";
        if(mysqli_query($con,$sql)){
            echo("1");
        }else{
            http_response_code(200);
        }
    }
?>