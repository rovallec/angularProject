<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';
    
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = ($request->justify);
    $res = [];

    $sql = "SELECT * FROM attendence_adjustemnt LEFT JOIN `attendence_justifications` ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification LEFT JOIN `hr_processes` ON hr_processes.idhr_processes = attendence_justifications.id_process WHERE attendence_justifications.idattendence_justifications = 1";

    if($result = mysqli_query($con, $sql)){
        while($res = mysqli_fetch_assoc($result)){
            $res['idattendence_adjustemnt'] = $result['idattendence_adjustemnt'];
            $res['id_attendence'] = $result['id_attendence'];
            $res['id_justification'] = $result['id_justification'];
            $res['time_before'] = $result['time_before'];
            $res['time_after'] = $result['time_after'];
            $res['amount'] = $result['amount'];
            $res['state'] = $result['state'];
            $res['id_process'] = $result['id_process'];
            $res['reason'] = $result['reason'];
            $res['id_user'] = $result['id_user'];
            $res['id_employee'] = $result['id_employee'];
            $res['id_type'] = $result['id_type'];
            $res['id_department'] = $result['id_department'];
            $res['date'] = $result['date'];
            $res['notes'] = $result['notes'];
            $res['status'] = $result['status'];
        }
        echo(json_encode($res));
    }else{
        http_response_code(404);
    }


?>