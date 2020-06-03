<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$get = [];
$i = 0;

$postdata = file_get_contents("php://input");
if(isset($postdata) && !empty($postdata)){
    $request = json_decode($postdata);
    $string = ($request->string);
    switch ($string) {
        case 'Recruiter':
            $sql = "SELECT `users`.`user_name` AS `name` FROM `users` WHERE `id_role` = 1;";
        break;
        case 'account':
            $sql = "SELECT `accounts`.`name` AS `name` FROM `accounts`;";
        break;
        case 'lastprocessName':
            $sql = "SELECT `process_templates`.`name` AS `name` FROM `process_templates` WHERE `id_role` = 1;";
        break;
    }
    if($res = mysqli_query($con, $sql)){
        while($rows = mysqli_fetch_assoc($res)){
            $get[$i] = $rows['name'];
            $i++;
        }
    }
    echo(json_encode($get));
}
?>