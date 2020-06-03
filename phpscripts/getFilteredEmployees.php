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


if($filter === 'name'){
    $sql = "SELECT `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`,  `profiles`.`dpi`
        FROM `employees`
        LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter`
        LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account`
        LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
        LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` WHERE `first_name` LIKE '%$value%' OR `second_name` LIKE '%$value%' OR `first_lastname` LIKE '%$value%' OR `second_lastname` LIKE '%$value%';";
}else{
    $sql = "SELECT `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `profiles`.`dpi` 
        FROM `employees`
        LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter`
        LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account`
        LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
        LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` WHERE `$filter` LIKE '%$value%';";
}

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
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