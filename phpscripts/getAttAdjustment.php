<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';
    
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = ($request->justify);
    $resp = [];

    if($id == explode(";", $id)[0] == 'id'){
        $emp =  explode(";", $id)[1];
        $sql = "SELECT * FROM attendence_adjustemnt LEFT JOIN `attendence_justifications` ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification LEFT JOIN `hr_processes` ON hr_processes.idhr_processes = attendence_justifications.id_process LEFT JOIN users ON users.idUser = hr_processes.id_user WHERE hr_processes.id_employee  = $emp";
    }
    $sql = "SELECT * FROM attendence_adjustemnt LEFT JOIN `attendence_justifications` ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification LEFT JOIN `hr_processes` ON hr_processes.idhr_processes = attendence_justifications.id_process LEFT JOIN users ON users.idUser = hr_processes.id_user WHERE attendence_justifications.idattendence_justifications  = $id";

    if($result = mysqli_query($con,$sql)){
        while($res = mysqli_fetch_assoc($result)){
            $resp['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
            $resp['id_attendence'] = $res['id_attendence'];
            $resp['id_justification'] = $res['id_justification'];
            $resp['time_before'] = $res['time_before'];
            $resp['time_after'] = $res['time_after'];
            $resp['amount'] = $res['amount'];
            $resp['state'] = $res['state'];
            $resp['id_process'] = $res['id_process'];
            $resp['reason'] = $res['reason'];
            $resp['id_user'] = $res['id_user'];
            $resp['id_employee'] = $res['id_employee'];
            $resp['id_type'] = $res['id_type'];
            $resp['id_department'] = $res['id_department'];
            $resp['date'] = $res['date'];
            $resp['notes'] = $res['notes'];
            $resp['status'] = $res['status'];
            $resp['id_user'] = $res['user_name'];
        }
        echo(json_encode($resp));
    }else{
        http_response_code(404);
    }


?>