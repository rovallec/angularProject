<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

    $postdata = file_get_contents("php://input");
    
    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);
        $idemployees = ($request->id_employee);
        $idschedule = ($request->id_schedule);
        $week_value = ($request->week_value);
        $id_period = ($request->id_period);

        $sql = "INSERT INTO rosters (idrosters, id_employee, id_period, id_type, week_value) VALUES (null, $idemployees, $id_period, $idschedule, '$week_value');";
        if(mysqli_query($con,$sql)){
            echo("1");
        }else{
            echo("0" + mysqli_error($con));
            http_response_code(200);
        }
    }
?>