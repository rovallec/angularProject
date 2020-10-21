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

$sql = "SELECT * FROM attendences LEFT JOIN attendence_adjustemnt ON attendence_adjustemnt.id_attendence = attendences.idattendences WHERE id_employee = $id AND `date` < '$date' AND `state` = 'PENDING'";


if($result = mysqli_query($con,$sql)){
    while($res = mysqli_fetch_assoc($result)){
        $adjustes[$i]['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
        $adjustes[$i]['id_attendence'] = $res['id_attendence'];
        $adjustes[$i]['id_justification'] = $res['id_justification'];
        $adjustes[$i]['time_before'] = $res['time_before'];
        $adjustes[$i]['time_after'] = $res['time_after'];
        $adjustes[$i]['amount'] = $res['amount'];
        $adjustes[$i]['state'] = $res['state'];
        $i++;
    }
}
echo(json_encode($adjustes));
?>