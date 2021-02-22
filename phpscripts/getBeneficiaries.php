<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $i = 0;
    if (empty($request->id) || is_null($request->id) || isset($request->id)) {
      exit;
    } else {
    $id = ($request->id);
    $return = [];

    $sql = "SELECT * FROM `beneficiaries` LEFT JOIN `insurances` ON `insurances`.`idinsurances` = `beneficiaries`.`id_insurance` WHERE `idinsurances` = $id;";

    if($result = mysqli_query($con, $sql)){
        while($res = mysqli_fetch_assoc($result)){
            $return[$i]['idbeneficiaries'] = $res['idinsurances'];
            $return[$i]['first_name'] = $res['first_name'];
            $return[$i]['second_name'] = $res['second_name'];
            $return[$i]['first_lastname'] = $res['first_lastname'];
            $return[$i]['second_lastname'] = $res['second_lastname'];
            $return[$i]['afinity'] = $res['afinity'];
            $i++;
        }
        echo(json_encode($return));
    }
  }
?>
