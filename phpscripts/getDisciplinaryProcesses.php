<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);
$return = [];
$i = [];

$sql = "SELECT * FROM `disciplinary_requests` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `disciplinary_requests`.`id_process` LEFT JOIN `disciplinary_processes` ON `disciplinary_processes`.`id_request` = `disciplinary_requests`.`iddisciplinary_requests`;";

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return[$i]['id_user'] = $res['id_user'];
        $return[$i]['id_employee'] = $res['id_employee'];
        $return[$i]['id_type'] = $res['id_type'];
        $return[$i]['id_department'] = $res['id_department'];
        $return[$i]['date'] = $res['date'];
        $return[$i]['notes'] = $res['notes'];
        $return[$i]['status'] = $res['status'];
        $return[$i]['idrequests'] = $res['iddisciplinary_requests'];
        $return[$i]['requested_by'] = $res['requested_by'];
        $return[$i]['reason'] = $res['reason'];
        $return[$i]['description'] = $res['description'];
        $return[$i]['resolution'] = $res['resolution'];
        $return[$i]['proceed'] = $res['proceed'];
        $return[$i]['iddp'] = $res['iddisciplinary_processes'];
        $return[$i]['type'] = $res['type'];
        $return[$i]['cathegory'] = $res['cathegory'];
        $return[$i]['dp_grade'] = $res['dp_grade'];
        $return[$i]['motive'] = $res['motive'];
        $return[$i]['imposition_date'] = $res['imposition_date'];
        $return[$i]['legal_foundament'] = $res['legal_foundament'];
        $return[$i]['consequences'] = $res['consequences'];
        $return[$i]['observations'] = $res['observations'];
        $i++;
    }
    echo(json_encode($return));
}else{
    http_response_code(404);
}
?>