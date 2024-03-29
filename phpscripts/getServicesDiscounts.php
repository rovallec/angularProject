<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$user = [];
$i = 0;
$date = ($request->date);

if(parse_str(explode("-",$date)[2]) > 15){
    $sql = "SELECT services.idservices, services.id_process, services.name, services.amount, services.max, services.frecuency, services.status, services.current,
            internal_processes.idinternal_processes, internal_processes.id_employee, internal_processes.id_user, internal_processes.name, internal_processes.date, services.type
            FROM `services` 
            LEFT JOIN `internal_processes` ON `internal_processes`.`idinternal_processes` = `services`.`id_process`
            LEFT JOIN `periods` ON `periods`.`end` = '$date' AND `services`.`frecuency` BETWEEN `periods`.`start` AND `periods`.`end`
            WHERE (`frecuency` = 'UNIQUE' OR `frecuency` = 'MONTHLY' OR  `frecuency` = 'BIWEEKLY' OR `periods`.`idperiods` IS NOT NULL) 
            AND `id_employee` = $id AND services.`status` = 1;";
}else{
    $sql = "SELECT services.idservices, services.id_process, services.name, services.amount, services.max, services.frecuency, services.status, services.current,
    internal_processes.idinternal_processes, internal_processes.id_employee, internal_processes.id_user, internal_processes.name, internal_processes.date, services.type
    FROM `services` 
    LEFT JOIN `internal_processes` ON `internal_processes`.`idinternal_processes` = `services`.`id_process`
    LEFT JOIN `periods` ON `periods`.`end` = '$date' AND `services`.`frecuency` BETWEEN `periods`.`start` AND `periods`.`end`
    WHERE (`frecuency` = 'UNIQUE' OR `frecuency` = 'BIWEEKLY' OR  `periods`.`idperiods` IS NOT NULL) AND `id_employee` = $id AND services.`status` = 1;";
}

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idservices'] = $row['idservices'];
        $user[$i]['id_process'] = $row['id_process'];
        $user[$i]['id_employee'] = $row['id_employee'];
        $user[$i]['name'] = $row['name'];
        $user[$i]['amount'] = $row['amount'];
        $user[$i]['max'] = $row['max'];
        $user[$i]['frecuency'] = $row['frecuency'];
        $user[$i]['status'] = $row['status'];
        $user[$i]['current'] = $row['current'];
        $user[$i]['type'] = $row['type'];
        $i = $i + 1;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
