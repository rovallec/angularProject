<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $result = [];

    $sql = "SELECT `employees`.*, `hires`.*, `accounts`.`name` AS `acc_name` FROM `employees` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` WHERE `id_profile` = $id;";

    if($res = mysqli_query($con, $sql)){
        while($r = mysqli_fetch_assoc($res)){
            $result['id_profile'] = $r['id_profile'];
            $result['idemployees']= $r['idemployees'];
            $result['id_hire'] = $r['id_hire'];
            $result['id_account'] = $r['acc_name'];
            $result['reporter'] = $r['reporter'];
            $result['client_id'] = $r['client_id'];
            $result['hiring_date'] = $r['hiring_date'];
            $result['job'] = $r['job'];
            $result['base_payment'] = $r['base_payment'];
            $result['productivity_payment'] = $r['productivity_payment'];
            $result['state'] = $r['state'];
        }
        echo(json_encode($result));
    }
?>