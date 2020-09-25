<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$filter = ($request->filter);
$value = ($request->value);
$dp = ($request->dp);

if($dp == '27'){
    $add_sql = "`employees`.`id_account` = '13' OR `employees`.`id_account` = '25' OR `employees`.`id_account` = '23' OR `employees`.`id_account` = '26' OR `employees`.`id_account` = '12'";
}else{
    if($dp == '5'){
        $add_sql = "`employees`.`id_account` = '1' OR `employees`.`id_account` = '3' OR `employees`.`id_account` = '6' OR `employees`.`id_account` = '7' OR `employees`.`id_account` = '8' OR `employees`.`id_account` = '9' OR `employees`.`id_account` = '10' OR `employees`.`id_account` = '14' OR `employees`.`id_account` = '15' OR `employees`.`id_account` = '16' OR `employees`.`id_account` = '17' OR `employees`.`id_account` = '18' OR `employees`.`id_account` = '19' OR `employees`.`id_account` = '20' OR `employees`.`id_account` = '21' OR `employees`.`id_account` = '22' OR `employees`.`id_account` = '24'";
    }
}
if($filter === 'name'){
    $sql = "SELECT `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`,  `profiles`.`dpi` FROM `employees` LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` WHERE (`first_name` LIKE '%$value%' OR `second_name` LIKE '%$value%' OR `first_lastname` LIKE '%$value%' OR `second_lastname` LIKE '%$value%') AND ($add_sql);";
}else{
    $sql = "SELECT `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `profiles`.`dpi`  FROM `employees` LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` WHERE (A`$filter` LIKE '%$value%') AND ($add_sql);";
}
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_profile'] = $row['id_profile'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['id_hire'] = $row['id_hire'];
        $res[$i]['id_account'] = $row['id_account'];
        $res[$i]['account'] = $row['name'];
        $res[$i]['user_name'] = $row['reporter'];
        $res[$i]['job'] = $row['job'];
        $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $i++;
    }
    echo(json_encode($res));
}
?>