<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$nm = ($request->department);
$res = [];
$i = 0;

if($nm=='27'){
    $sql = "SELECT `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender
        FROM `employees`
        LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter`
        LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account`
        LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
        LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile)
        LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` WHERE `employees`.`id_account` = '13' OR `employees`.`id_account` = '25' OR `employees`.`id_account` = '23' OR `employees`.`id_account` = '26' OR `employees`.`id_account` = '12' LIMIT 20;";
}else{
    if($nm== '5' || $nm == '29'){
        $sql = "SELECT `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender
        FROM `employees`
        LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter`
        LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account`
        LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
        LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile)
        LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`  WHERE `employees`.`id_account` = '1' OR `employees`.`id_account` = '3' OR `employees`.`id_account` = '6' OR `employees`.`id_account` = '7' OR `employees`.`id_account` = '8' OR `employees`.`id_account` = '9' OR `employees`.`id_account` = '10' OR `employees`.`id_account` = '14' OR `employees`.`id_account` = '15' OR `employees`.`id_account` = '16' OR `employees`.`id_account` = '17' OR `employees`.`id_account` = '18' OR `employees`.`id_account` = '19' OR `employees`.`id_account` = '20' OR `employees`.`id_account` = '21' OR `employees`.`id_account` = '22' OR `employees`.`id_account` = '24' OR `employees`.`id_account` = '30' OR `employees`.`id_account` = '31' OR `employees`.`id_account` = '5' OR `employees`.`id_account` = '44' LIMIT 20;";
    }else{
        if($nm == 'all'){
            $sql = "SELECT `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender
            FROM `employees`
            LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter`
            LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account`
            LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
            LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile)
            LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LIMIT 50;";
        } else if ($nm == 'NoLimitAC') {
            $sql = "SELECT `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender
            FROM `employees`
            LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter`
            LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account`
            LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
            LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile)
            LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`
            WHERE `employees`.`active` = 1;";
        }

    }
}
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_profile'] = $row['idprofiles'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['id_hire'] = $row['id_hire'];
        $res[$i]['id_account'] = $row['id_account'];
        $res[$i]['account'] = $row['name'];
        $res[$i]['user_name'] = $row['reporter'];
        $res[$i]['job'] = $row['job'];
        $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['hiring_date'] = $row['hiring_date'];
        $res[$i]['platform'] = $row['platform'];
        $res[$i]['state'] = $row['state'];
        $res[$i]['children'] = $row['children'];
        $res[$i]['gender'] = $row['gender'];
        $i++;
    }
    echo(json_encode($res));
}
?>
