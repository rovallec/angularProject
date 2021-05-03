<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $return = [];
    $i = 0;

    $sql = "SELECT `employees`.`idemployees`, `hires`.*, `hr_processes`.*, `hr_processes`.*, `users`.*, `accounts`.`name` as `departmet`, `leaves`.* , `process_types`.`idprocess_types`, `process_types`.`name` as `type` FROM `leaves` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `leaves`.`id_process` LEFT JOIN `process_types` ON `process_types`.`idprocess_types` = `hr_processes`.`id_type` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `hr_processes`.`id_department` LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` WHERE `id_profile` = $id;";

    if($result = mysqli_query($con, $sql)){
        while($res = mysqli_fetch_assoc($result)){
            $return[$i]['id_process'] = $res['idhr_processes'];
            $return[$i]['id_user'] = $res['id_user'];
            $return[$i]['id_employee'] = $id;
            $return[$i]['id_type'] = $res['type'];
            $return[$i]['id_department'] = $res['department'];
            $return[$i]['date'] = $res['date'];
            $return[$i]['notes'] = $res['notes'];
            $return[$i]['status'] = $res['status'];
            $return[$i]['motive'] = $res['motive'];
            $return[$i]['approved_by'] = $res['approved_by'];
            $return[$i]['start'] = $res['start'];
            $return[$i]['end'] = $res['end'];
            $return[$i]['dateTime'] = $res['time'];
            $i++;
        }
        echo(json_encode($return));
    }
?>