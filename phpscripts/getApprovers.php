<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $result = [];
    $r = [];
    $i = 0;

    $sql = "SELECT * FROM `users` WHERE `id_role` = '7';";

    if($res = mysqli_query($con, $sql)){
        while($result = mysqli_fetch_assoc($res)){
            $r[$i]['iduser'] = $result['idUser'];
            $r[$i]['username'] = $result['username'];
            $r[$i]['department'] = $result['department'];
            $r[$i]['user_name'] = $result['user_name'];
            $r[$i]['valid'] = $result['valid'];
            $r[$i]['id_role'] = $result['id_role'];
            $i++;
        };
        echo(json_encode($r));
    }
?>