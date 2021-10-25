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
$rol = ($request->rol);

if($dp != '4' && $dp != '28'){
    if($dp == '27'){
        $add_sql = "`employees`.`id_account` = '13' OR `employees`.`id_account` = '25' OR `employees`.`id_account` = '23' OR `employees`.`id_account` = '26' OR `employees`.`id_account` = '12' OR `employees`.`id_account` = '27' OR `employees`.`id_account` = '33' OR `employees`.`id_account` = '44'";
    }else{
        if($dp == '5' || $dp == '29'){
            if($rol != '13'){
                $add_sql = "`employees`.`id_account` = '1' OR `employees`.`id_account` = '3' OR `employees`.`id_account` = '6' OR `employees`.`id_account` = '7' OR `employees`.`id_account` = '8' OR `employees`.`id_account` = '9' OR `employees`.`id_account` = '10' OR `employees`.`id_account` = '14' OR `employees`.`id_account` = '15' OR `employees`.`id_account` = '16' OR `employees`.`id_account` = '17' OR `employees`.`id_account` = '18' OR `employees`.`id_account` = '19' OR `employees`.`id_account` = '20' OR `employees`.`id_account` = '21' OR `employees`.`id_account` = '22' OR `employees`.`id_account` = '24' OR `employees`.`id_account` = '30' OR `employees`.`id_account` = '31' OR `employees`.`id_account` = '38' OR `employees`.`id_account` = '39' OR `employees`.`id_account` = '43' OR `employees`.`id_account` = '44' OR `employees`.`id_account` = '45'";
            }else{
                $add_sql = "1";
            }
        }else{
            if($dp == 'all'){
                $add_sql = "1";
            }
        }
    }
    if($filter === 'name'){
        $sql = "SELECT profiles.dpi, `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender 
            FROM `employees` 
            LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` 
            LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` 
            LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` 
            LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` 
            LEFT JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = hires.id_profile)
            LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile) 
            WHERE (p2.name LIKE '%$value%') AND ($add_sql);";
    }else{
        if($dp == 'exact'){
            $sql = "SELECT profiles.dpi, `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender FROM `employees` LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile) WHERE (`$filter` = '$value');";
        }else{
            $sql = "SELECT profiles.dpi, `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender FROM `employees` LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile) WHERE (`$filter` LIKE '%$value%') AND ($add_sql);";
        }
    }
}else{
    if($filter === 'name'){
        $sql = "SELECT profiles.dpi, `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender 
        FROM `employees` 
        LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` 
        LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` 
        LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` 
        LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` 
        LEFT JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = hires.id_profile)
        LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile) 
        WHERE (p2.name LIKE '%$value%');";
    }else{
        $sql = "SELECT profiles.dpi, `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender FROM `employees` LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile) WHERE (`$filter` LIKE '%$value%');";
    }
}
if($filter === 'client_id'){
    $sql = "SELECT profiles.dpi, `profiles`.`idprofiles`, `employees`.*, `hires`.`id_profile`, `hires`.`nearsol_id`, `users`.`user_name`, `accounts`.`name`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, coalesce(a.children, 0) AS children, profiles.gender FROM `employees` LEFT JOIN `users` ON `users`.`idUser` = `employees`.`reporter` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN (select count(families.relationship) as children, families.id_profile from families where families.relationship in('hijo', 'hija', 'son','daughter')  group by families.id_profile) a  ON (a.id_profile = hires.id_profile) WHERE (`$filter` = '$value');";
}
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_profile'] = $row['id_profile'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['id_hire'] = $row['id_hire'];
        $res[$i]['id_account'] = $row['id_account'];
        $res[$i]['account'] = $row['name'];
        $res[$i]['user_name'] = $row['user_name'];
        $res[$i]['job'] = $row['job'];
        $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['base_payment'] = $row['base_payment'];
        $res[$i]['productivity_payment'] = $row['productivity_payment'];
        $res[$i]['hiring_date'] = $row['hiring_date'];
        $res[$i]['active'] = $row['active'];
        $res[$i]['status'] = $row['state'];
        $res[$i]['reporter'] = $row['reporter'];
        $res[$i]['dpi'] = $row['dpi'];
        $res[$i]['society'] = $row['society'];
        $res[$i]['children'] = $row['children'];
        $res[$i]['gender'] = $row['gender'];
        $i++;
    }
    echo(json_encode($res));
}
?>