<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $new_id = ($request->new_id);
    $id_employee = ($request->id_employee);
    //process
    $idinternal_process = ($request->idinternal_process);
    $id_user = ($request->id_user);
    $proc_name = ($request->proc_name);
    $date = ($request->date);
    $proc_status = ($request->proc_status);
    $notes = ($request->notes);
    $old = ($request->old_id);

    $notes_proc = $old . "|" . $new_id;
    
    $sql = "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES (null, '$id_user', '$id_employee', '$proc_name', '$date', '$proc_status', '$notes_proc');";
    $sql2 = "UPDATE `employees` SET `client_id`  = '$new_id' WHERE `idemployees` = $id_employee;";

    if(mysqli_query($con, $sql)){
        if(mysqli_query($con,$sql2)){
            http_response_code(200);
        }else{
            http_response_code(404);
        }
    }
?>