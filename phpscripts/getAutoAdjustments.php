<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$date = ($request->date);

$i = 0;
$adjustes = [];

$sql = "SELECT * FROM attendences
            LEFT JOIN attendence_adjustemnt ON attendence_adjustemnt.id_attendence = attendences.idattendences 
            INNER JOIN attendence_justifications ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification
            INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
            INNER JOIN employees ON employees.idemployees = attendences.id_employee WHERE attendences.id_employee = $id AND attendences.`date` < '$date'";


if($result = mysqli_query($con,$sql)){
    while($res = mysqli_fetch_assoc($result)){
        $adjustes[$i]['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
        $adjustes[$i]['id_attendence'] = $res['id_attendence'];
        $adjustes[$i]['id_justification'] = $res['id_justification'];
        $adjustes[$i]['time_before'] = $res['time_before'];
        $adjustes[$i]['time_after'] = $res['time_after'];
        $adjustes[$i]['amount'] = $res['amount'];
        $adjustes[$i]['state'] = $res['state'];
        $adjustes[$i]['date'] = $res['date'];
        $adjustes[$i]['account'] = $res['employees.id_account'];
        $adjustes[$i]['dateTime'] = $res['time'];
        $i++;
    }
}
echo(json_encode($adjustes));
?>