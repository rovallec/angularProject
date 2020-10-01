<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $i = 0;
    $id = ($request->id);
    $return = [];
    $date = date("Y-m-d");

    $dt = date('Y-m-d', strtotime($date . " - 10 month"));
    $sql = "SELECT * FROM `periods` WHERE `start` BETWEEN $date AND $dt;";
    echo($sql);
?>