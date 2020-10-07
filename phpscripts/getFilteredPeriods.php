<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $return = [];
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $sql = "SELECT * FROM `periods` WHERE `idperiods` = $id;";

    if($result = mysqli_query($con, $sql)){
        while($res = mysqli_fetch_assoc($result)){
            $return['idperiods'] = $res['idperiods'];
            $return['start'] = $res['start'];
            $return['end'] = $res['end'];
            $return['status'] = $res['status'];
        }
        echo(json_encode($return));
    }
?>