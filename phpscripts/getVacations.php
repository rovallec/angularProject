<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);

    $rs = [];
    $i = 0;
    $sql = "SELECT `hr_processes`.*, `hr_processes`.*, `users`.*, `accounts`.`name` as `departmet`, `vacations`.idvacations, `vacations`.`id_process`, `vacations`.`action`, 
    `vacations`.`count`, `vacations`.`date` as dt FROM `vacations` 
    LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `vacations`.`id_process` 
    LEFT JOIN `process_types` ON `process_types`.`idprocess_types` = `hr_processes`.`id_type` 
    LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user`
    LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `hr_processes`.`id_department`
    WHERE `id_employee` = $id;"

    if($result = mysqli_query($con, $sql)){
        while ($row = mysqli_fetch_assoc($result)) {
            $rs[$i]['id_user'] = $row['user_name'];
            $rs[$i]['id_employee'] = $row['id_employee'];
            $rs[$i]['id_type'] = $row['name'];
            $rs[$i]['id_department'] = $row['department'];
            $rs[$i]['date'] = $row['date'];
            $rs[$i]['notes'] = $row['notes'];
            $rs[$i]['status'] = $row['status'];
            $rs[$i]['action'] = $row['action'];
            $rs[$i]['count'] = $row['count'];
            $rs[$i]['took_date'] = $row['dt'];
            $i++;
        }
    }
?>