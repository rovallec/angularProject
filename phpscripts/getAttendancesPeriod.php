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

$sql = "SELECT * FROM attendences where id_employee = $id AND (`date` BETWEEN '$date_1' AND '$date_2');";

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return[$i]['idattendences'] = $res['idattendences'];
        $return[$i]['id_employee'] = $res['id_employee'];
        $return[$i]['date'] = $res['date'];
        $return[$i]['scheduled'] = $res['scheduled'];
        $return[$i]['worked_time'] = $res['worked_time'];
        $i++;
    }
    echo(json_encode($return));
}else{
    http_response_code(404);
}
?>