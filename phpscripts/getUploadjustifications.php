<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$i = 0;
$adjustes = [];
$sql = "SELECT attendences.date AS `attdate`, attendence_adjustemnt.*,  profiles.*, hires.*, employees.*, hr_processes.*, accounts.name AS `acn`, attendence_justifications.* FROM attendence_justifications
        INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process AND attendence_justifications.id_import = $id
        INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications 
        INNER JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence
        INNER JOIN employees ON employees.idemployees = attendences.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN accounts ON accounts.idaccounts = employees.id_account
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile";
if($result = mysqli_query($con,$sql)){
    while($res = mysqli_fetch_assoc($result)){
        $adjustes[$i]['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
        $adjustes[$i]['id_attendence'] = $res['client_id'];
        $adjustes[$i]['id_justification'] = $res['id_justification'];
        $adjustes[$i]['time_before'] = $res['time_before'];
        $adjustes[$i]['time_after'] = $res['time_after'];
        $adjustes[$i]['amount'] = $res['amount'];
        $adjustes[$i]['state'] = $res['state'];
        $adjustes[$i]['id_process'] = $res['idhr_processes'];
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
        $adjustes[$i]['account'] = $res['acn'];
        $adjustes[$i]['dateTime'] = $res['time'];
        $i++;
    }
    echo(json_encode($adjustes));
}else{
    echo(mysqli_error($con));
}

?>