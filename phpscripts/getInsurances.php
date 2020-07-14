<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $return = [];

    $sql = "SELECT * FROM `insurances` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `insurances`.`id_process` LEFT JOIN `employees` ON `employees`.`idemployees` = `id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` WHERE `id_profile` = $id;";

    if($result = mysqli_query($con, $sql)){
        while($res = mysqli_fetch_assoc($result)){
            $return['idinsurances'] = $res['idinsurances'];
            $return['id_process'] = $res['id_process'];
            $return['plan'] = $res['plan'];
            $return['license'] = $res['license'];
            $return['cert'] = $res['cert'];
            $return['contractor'] = $res['contractor'];
            $return['place'] = $res['place'];
            $return['reception'] = $res['reception'];
            $return['delivered'] = $res['delivered'];
            $return['status'] = $res['status'];
        }
        echo(json_encode($return));
    }
?>