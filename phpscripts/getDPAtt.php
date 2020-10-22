<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);
$date_1 = ($request->date_1);
$date_2 = ($request->date_2);
$return = [];
$i = 0;

$sql = "SELECT id_employee, day_1, day_2, day_3, day_4, imposition_date FROM suspensions 
        LEFT JOIN disciplinary_processes on disciplinary_processes.iddisciplinary_processes = suspensions.id_disciplinary_process 
        LEFT JOIN disciplinary_requests on disciplinary_requests.iddisciplinary_requests = disciplinary_processes.id_request 
        LEFT JOIN hr_processes on hr_processes.idhr_processes = disciplinary_requests.id_process 
        WHERE id_employee = $id AND ((day_1 between '$date_1' AND '$date_2') OR (day_2 between '$date_1' AND '$date_2') OR (day_3 between '$date_1' AND '$date_2') OR (day_4 between '$date_1' AND '$date_2'));";

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return[$i]['imposition_date'] = $res['imposition_date'];
        $return[$i]['day_1'] = $res['day_1'];
        $return[$i]['day_2'] = $res['day_2'];
        $return[$i]['day_3'] = $res['day_3'];
        $return[$i]['day_4'] = $res['day_4'];
        $i++;
    }
    echo(json_encode($return));
}else{
    http_response_code(404);
}
?>