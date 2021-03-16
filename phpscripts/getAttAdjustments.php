<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$id = $id . ";";
$i = 0;
$adjustes = [];
if(explode(";", $id)[0] == 'id|p'){
    $temp = explode(";",$id)[1];
            $emp = explode("|",$temp)[0];
            $period = explode("|",$temp)[1];
            $sql =  "SELECT *,  `attendences`.`date` AS `attdate` FROM attendence_adjustemnt 
            LEFT JOIN `attendences` ON `attendences`.`idattendences` = `attendence_adjustemnt`.`id_attendence` 
            LEFT JOIN `attendence_justifications` ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification 
            LEFT JOIN `hr_processes` ON hr_processes.idhr_processes = attendence_justifications.id_process 
            LEFT JOIN employees ON employees.idemployees = hr_processes.id_employee
            LEFT JOIN hires ON hires.idhires = employees.id_hire
            LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
            LEFT JOIN users ON users.idUser = hr_processes.id_user WHERE hr_processes.id_employee  = $emp AND attendences.date BETWEEN $period;";
}else{
    if(explode(";", $id)[0] == 'id|p|t'){
        $period = explode(";",$id)[1];
        $sql =  "SELECT *,  `attendences`.`date` AS `attdate` FROM attendence_adjustemnt 
        LEFT JOIN `attendences` ON `attendences`.`idattendences` = `attendence_adjustemnt`.`id_attendence` 
        LEFT JOIN `attendence_justifications` ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification 
        LEFT JOIN `hr_processes` ON hr_processes.idhr_processes = attendence_justifications.id_process 
        LEFT JOIN employees ON employees.idemployees = hr_processes.id_employee
        LEFT JOIN hires ON hires.idhires = employees.id_hire
        LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
        LEFT JOIN users ON users.idUser = hr_processes.id_user WHERE attendence_justifications.reason = 'Closing Exception' AND attendences.date BETWEEN $period;";
    }else{
        $sql = "SELECT *,  `attendences`.`date` AS `attdate` FROM attendence_adjustemnt 
        LEFT JOIN `attendences` ON `attendences`.`idattendences` = `attendence_adjustemnt`.`id_attendence` 
        LEFT JOIN `attendence_justifications` ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification 
        LEFT JOIN `hr_processes` ON hr_processes.idhr_processes = attendence_justifications.id_process 
        LEFT JOIN employees ON employees.idemployees = hr_processes.id_employee
        LEFT JOIN hires ON hires.idhires = employees.id_hire
        LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
        LEFT JOIN users ON users.idUser = hr_processes.id_user WHERE `hr_processes`.`id_employee` = '$id' AND `id_type` = '2';";
    }
}


if($result = mysqli_query($con,$sql)){
    while($res = mysqli_fetch_assoc($result)){
        $adjustes[$i]['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
        $adjustes[$i]['id_attendence'] = $res['id_attendence'];
        $adjustes[$i]['id_justification'] = $res['id_justification'];
        $adjustes[$i]['time_before'] = $res['time_before'];
        $adjustes[$i]['time_after'] = $res['time_after'];
        $adjustes[$i]['amount'] = $res['amount'];
        $adjustes[$i]['state'] = $res['state'];
        $adjustes[$i]['id_process'] = $res['id_process'];
        $adjustes[$i]['reason'] = $res['reason'];
        $adjustes[$i]['id_user'] = $res['user_name'];
        $adjustes[$i]['id_employee'] = $res['id_employee'];
        $adjustes[$i]['id_type'] = $res['id_type'];
        $adjustes[$i]['id_department'] = $res['id_department'];
        $adjustes[$i]['date'] = $res['date'];
        $adjustes[$i]['notes'] = $res['notes'];
        $adjustes[$i]['status'] = $res['status'];
        $adjustes[$i]['attendance_date'] = $res['attdate'];
        $adjustes[$i]['name'] = $res['first_name'] . " " . $res['second_name'] . " " . $res['first_lastname'] . " " . $res['second_lastname'];
        $adjustes[$i]['nearsol_id'] = $res['nearsol_id'];
        $adjustes[$i]['error'] = "SUCCESS";
        $i++;
    }
}
echo(json_encode($adjustes));
?>