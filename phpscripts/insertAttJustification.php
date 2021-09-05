<?php
    require 'database.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id_user = ($request->id_user);
    $id_employee = ($request->id_employee);
    $id_type = ($request->id_type);
    $id_department = ($request->id_department);
    $date = ($request->date);
    $notes = ($request->notes);
    $status = ($request->state);
    $start = ($request->start);
    $end = ($request->end);
    $id_import = '0';
    try {
        $id_import = ($request->id_import);
    } catch (\Throwable $th) {
        
    }
    $str = "";

    $sql = "INSERT INTO `hr_processes`(`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`, `id_import`) VALUES (NULL, '$id_user', '$id_employee', '$id_type', '$id_department', '$date', '$notes', '$status', '$id_import');";

    if(mysqli_query($con, $sql)){
        $idprocess = mysqli_insert_id($con);
        $reason = ($request->reason);
        $sql2 = "INSERT INTO `attendence_justifications`(`idattendence_justifications`, `id_process`, `reason`) VALUES (NULL, '$idprocess', '$reason');";

        if(mysqli_query($con, $sql2)){
            $id_justification = mysqli_insert_id($con);
            $idattendences = ($request->id_attendence);
            $time_before = ($request->time_before);
            $time_after = ($request->time_after);
            $amount = ($request->amount);
            $state = ($request->state);
            $sql3 = "INSERT INTO `attendence_adjustemnt`(`idattendence_adjustemnt`, `id_attendence`, `id_justification`, `time_before`, `time_after`, `amount`, `state`, `start`, `end`) VALUES (NULL, '$idattendences', '$id_justification', '$time_before', '$time_after', '$amount', '$state', '$start', '$end');";
            if(mysqli_query($con, $sql3)){
                if($reason != "Closing Exception"){
                    $sql4 = "UPDATE `attendences` SET `worked_time`= '$time_after' WHERE `idattendences` = '$idattendences';";
                    if(mysqli_query($con, $sql4)){
                        echo("1");
                    }else{
                        $str = $sql4 . "|" . mysqli_error($con);
                        echo(json_encode($str));
                    }
                }else{
                    echo("1");
                }
            }else{
                $str = $sql3 . "|" . mysqli_error($con);
                echo(json_encode($str));
            }
        }else{
            $str = $sql2 . "|" . mysqli_error($con);
            echo(json_encode($str));
        }
    }else{
        $str = $sql . "|" . mysqli_error($con);
        echo(json_encode($str));
    }
?>