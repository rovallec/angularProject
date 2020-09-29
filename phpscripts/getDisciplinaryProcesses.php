<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);
$return = [];
$i = 0;
if($id == 'all'){
    $sql = "SELECT `users`.*, `hires`.`id_profile`, `hires`.*, `suspensions`.*, `disciplinary_requests`.*, `hr_processes`.*, `disciplinary_processes`.*, `audiences`.`date` AS `audience_date`, `audiences`.`time`, `audiences`.`comments`, `audiences`.`status` AS `audience_status` FROM `disciplinary_requests` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `disciplinary_requests`.`id_process` LEFT JOIN `disciplinary_processes` ON `disciplinary_processes`.`id_request` = `disciplinary_requests`.`iddisciplinary_requests` LEFT JOIN `audiences` ON `audiences`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `suspensions` ON `suspensions`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user`;";
}else{
    if($id == 'active'){
        $sql = "SELECT `users`.*, `hires`.`id_profile`, `hires`.*, `suspensions`.*, `disciplinary_requests`.*, `hr_processes`.*, `disciplinary_processes`.*, `audiences`.`date` AS `audience_date`, `audiences`.`time`, `audiences`.`comments`, `audiences`.`status` AS `audience_status` FROM `disciplinary_requests` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `disciplinary_requests`.`id_process` LEFT JOIN `disciplinary_processes` ON `disciplinary_processes`.`id_request` = `disciplinary_requests`.`iddisciplinary_requests` LEFT JOIN `audiences` ON `audiences`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `suspensions` ON `suspensions`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` WHERE `hr_processes`.`status` = 'DISPATCHED';";
    }else{
        $sql = "SELECT `users`.*, `hires`.`id_profile`, `hires`.*, `suspensions`.*, `disciplinary_requests`.*, `hr_processes`.*, `disciplinary_processes`.*, `audiences`.`date` AS `audience_date`, `audiences`.`time`, `audiences`.`comments`, `audiences`.`status` AS `audience_status` FROM `disciplinary_requests` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `disciplinary_requests`.`id_process` LEFT JOIN `disciplinary_processes` ON `disciplinary_processes`.`id_request` = `disciplinary_requests`.`iddisciplinary_requests` LEFT JOIN `audiences` ON `audiences`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `suspensions` ON `suspensions`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` WHERE `id_profile` = $id;";
    }
}

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return[$i]['id_processes'] = $res['idhr_processes'];
        $return[$i]['id_user'] = $res['user_name'];
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
        $return[$i]['consequences'] = $res['consequences'];
        $return[$i]['cathegory'] = $res['cathegory'];
        $return[$i]['dp_grade'] = $res['dp_grade'];
        $return[$i]['motive'] = $res['motive'];
        $return[$i]['imposition_date'] = $res['imposition_date'];
        $return[$i]['legal_foundament'] = $res['legal_foundament'];
        $return[$i]['observations'] = $res['observations'];
        $return[$i]['audience_date'] = $res['audience_date'];
        $return[$i]['time'] = $res['time'];
        $return[$i]['comments'] = $res['comments'];
        $return[$i]['audience_status'] = $res['audience_status'];
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