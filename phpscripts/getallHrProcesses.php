<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "SELECT `hr_processes`.*, `users`.`user_name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, 
        `hires`.`nearsol_id`, `employees`.`client_id`, `process_types`.`name`
        FROM `hr_processes` 
        LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` 
        LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` 
        LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` 
        LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`
        LEFT JOIN `process_types` ON `process_types`.`idprocess_types` = `hr_processes`.`id_type` ORDER BY `idhr_processes` DESC LIMIT 20;";

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_employee'] = $row['id_employee'];
        $res[$i]['user_name'] = $row['user_name'];
        $res[$i]['employee'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['date'] = $row['date'];
        $res[$i]['status'] = $row['status'];
        $i++;
    }
    echo(json_encode($res));
}
?>