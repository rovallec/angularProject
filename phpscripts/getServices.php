<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $r = [];
    $i = 0;

    $sql = "SELECT users.user_name, services.*, internal_processes.notes, internal_processes.idinternal_processes, internal_processes.id_user, internal_processes.id_employee, internal_processes.name AS `proc_name`, internal_processes.date, internal_processes.status AS `proc_status` FROM `services` LEFT JOIN `internal_processes` ON `internal_processes`.`idinternal_processes` = `services`.`id_process` LEFT JOIN `users` ON `users`.`idUser` = `internal_processes`.`id_user` WHERE `id_employee` = $id";
    if($res = mysqli_query($con, $sql)){
        while($result = mysqli_fetch_assoc($res)){
            $r[$i]['idservices'] = $result['idservices'];
            $r[$i]['id_process'] = $result['id_process'];
            $r[$i]['id_employee'] = $result['id_employee'];
            $r[$i]['name'] = $result['name'];
            $r[$i]['amount'] = $result['amount'];
            $r[$i]['max'] = $result['max'];
            $r[$i]['frecuency'] = $result['frecuency'];
            $r[$i]['status'] = $result['status'];
            $r[$i]['current'] = $result['current'];
            $r[$i]['idinternal_process'] = $result['idinternal_processes'];
            $r[$i]['id_user'] = $result['user_name'];
            $r[$i]['proc_name'] = $result['proc_name'];
            $r[$i]['date'] = $result['date'];
            $r[$i]['proc_status'] = $result['proc_status'];
            $r[$i]['notes'] = $result['notes'];
            $r[$i]['type'] = $result['type'];
            $i++;
        };
        echo(json_encode($r));
    }
?>