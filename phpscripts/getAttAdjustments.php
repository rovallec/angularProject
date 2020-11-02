<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);

$i = 0;
$adjustes = [];

$sql = "SELECT `attendences`.`date` AS `attdate`, `users`.*, `hr_processes`.*, `attendence_justifications`.*, `attendence_adjustemnt`.* FROM `hr_processes` LEFT JOIN `attendence_justifications` ON `attendence_justifications`.`id_process` = `hr_processes`.`idhr_processes` LEFT JOIN `attendence_adjustemnt` ON `attendence_adjustemnt`.`id_justification` = `attendence_justifications`.`idattendence_justifications` LEFT JOIN `attendences` ON `attendences`.`idattendences` = `attendence_adjustemnt`.`id_attendence` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` WHERE `hr_processes`.`id_employee` = '$id' AND `id_type` = '2';";


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
        $i++;
    }
}
echo(json_encode($adjustes));
?>