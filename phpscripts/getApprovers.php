<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $result = [];

    $sql = "SELECT * FROM `users` WHERE `id_role` = '6';";

    if($res = mysqli_query($con, $sql)){
        echo(json_encode($res));
    }
?>