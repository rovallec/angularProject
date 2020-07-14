<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $return = [];

    $sql = "SELECT * FROM `beneficiaries` LEFT JOIN `insurances` ON `insurances`.`idinsurances` = `beneficiaries`.`id_insurance` WHERE `idinsurances` = $id;";

    if($result = mysqli_query($con, $sql)){
        while($res = mysqli_fetch_assoc($result)){
            $return[$i]['idbeneficiaries'] = $res['idinsurances'];
            $return[$i]['first_name'] = $res['id_process'];
            $return[$i]['second_name'] = $res['plan'];
            $return[$i]['first_lastname'] = $res['license'];
            $return[$i]['second_lastname'] = $res['cert'];
            $return[$i]['afinity'] = $res['contractor'];
            $i++;
        }
        echo(json_encode($return));
    }
?>