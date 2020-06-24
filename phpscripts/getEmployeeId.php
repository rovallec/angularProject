<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id = ($request->id);
    $result = [];

    $sql = "SELECT * FROM `employees` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` WHERE `id_profile` = $id;";

    if($res = mysqli_query($con, $sql)){
        echo(json_encode($res));
    }
?>