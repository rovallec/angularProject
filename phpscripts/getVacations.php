<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);

    $rs = [];
    $i = 0;
    $sql = "SELECT p.idprofiles, hp.*, u.*, a.name as `departmet`, 
                v.idvacations, 
                v.id_process, 
                v.action, 
                v.`count`, 
                v.`date` as dt, 
                pt.idprocess_types, 
                pt.name as `type`, 
                e.*, 
                h.* 
            FROM employees e
            INNER JOIN hires h ON h.idhires = e.id_hire 
            INNER JOIN profiles p ON p.idprofiles = h.id_profile
            INNER JOIN hr_processes hp on e.idemployees = hp.id_employee
            INNER JOIN vacations v ON hp.idhr_processes = v.id_process
            INNER JOIN accounts a ON a.idaccounts = hp.id_department
            LEFT JOIN process_types pt ON pt.idprocess_types = hp.id_type 
            LEFT JOIN users u ON u.idUser = hp.id_user
            WHERE p.idprofiles = $id;";
    if($result = mysqli_query($con, $sql)){
        while ($row = mysqli_fetch_assoc($result)) {
            $rs[$i]['id_process'] = $row['idhr_processes'];
            $rs[$i]['id_user'] = $row['user_name'];
            $rs[$i]['id_employee'] = $row['id_employee'];
            $rs[$i]['id_type'] = $row['type'];
            $rs[$i]['id_department'] = $row['department'];
            $rs[$i]['date'] = $row['date'];
            $rs[$i]['notes'] = $row['notes'];
            $rs[$i]['status'] = $row['status'];
            $rs[$i]['action'] = $row['action'];
            $rs[$i]['count'] = $row['count'];
            $rs[$i]['took_date'] = $row['dt'];
            $rs[$i]['dateTime'] = $row['time'];
            $i++;
        }
    }
    echo(json_encode($rs));
?>