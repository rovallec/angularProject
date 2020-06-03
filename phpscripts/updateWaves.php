<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

    $postdata = file_get_contents("php://input");
    
    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);
        $idwaves = ($request->idwaves);
        $id_account = ($request->id_account);
        $starting_date = ($request->starting_date);
        $end_date = ($request->end_date);
        $max_recriut = ($request->max_recriut);
        $hires = ($request->hires);
        $name = ($request->name);
        $trainning_schedule = ($request->trainning_schedule);
        $prefix = ($request->prefix);
        $ops_start = ($request->ops_start);
        $state = ($request->state);
        $account_name = ($request->account_name);

        $sql = "UPDATE `waves` SET `id_account`= '$id_account',`starting_date`= '$starting_date',`end_date`= '$end_date',`max_recriut`= '$max_recriut',`hires`= '$hires',`name`= '$name', `trainning_schedule` = '$trainning_schedule',`prefix`= '$prefix', `ops_start` = '$ops_start', `state`= '$state' WHERE `idwaves` = '$idwaves'";
        if(mysqli_query($con,$sql)){
            echo("1");
        }else{
            http_response_code(200);
        }
    }
?>