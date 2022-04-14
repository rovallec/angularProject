<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $idjudicials = ($request->idjudicials);
    $id_process = ($request->id_process);
    $amount = ($request->amount);
    $current = ($request->current);
    $max = ($request->max);
    
    $sql = "INSERT INTO `judicials` (`idjudicials`, `id_process`, `amount`, `current`, `max`) VALUES (null, '$id_process', '$amount', $current, '$max');";

    if(mysqli_query($con, $sql)){
            http_response_code(200);
        }else{
            http_response_code(404);
    }
?>