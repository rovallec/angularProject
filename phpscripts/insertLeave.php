<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id_user = ($request->id_user);
    $id_employee = ($request->id_employee);
    $id_type = ($request->id_type);
    $id_department = ($request->id_department);
    $date = ($request->date);
    $notes = ($request->notes);
    $status = ($request->status);
    $motive = ($request->motive);
    $approved_by = ($request->approved_by);
    $start = ($request->start);
    $end = ($request->end);
    
    $sql = "INSERT INTO `hr_processes` (`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`) VALUES (null, '$id_user', '$id_employee', '$id_type', '$id_department', '$date', '$notes', '$status');";

    if(mysqli_query($con, $sql)){
        $idprocesses = mysqli_insert_id($con);
        $sql2 = "INSERT INTO `leaves` (`idleaves`, `id_process`, `motive`, `approved_by`, `start`, `end`) VALUES (null, '$idprocesses', '$motive', '$approved_by', '$start', '$end');";
        if(mysqli_query($con, $sql2)){
            echo(mysqli_insert_id($con));
        }else{
            http_response_code(404);
        }
    }
?>