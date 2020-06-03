<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_role = ($request->id_role);
$id_profile = ($request->id_profile);

$proccesses = [];
$i = 0;


$sql = "SELECT `processes`.*, `users`.* FROM `processes` LEFT JOIN `users` ON `processes`.`id_user` = `users`.`idUser` WHERE `processes`.`id_role` = '$id_role' AND `processes`.`id_profile` = '$id_profile'";
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            $proccesses[$i]['idprocesses'] = $row['idprocesses'];
            $proccesses[$i]['name'] = $row['name'];
            $proccesses[$i]['descritpion'] = $row['description'];
            $proccesses[$i]['prc_date'] = $row['prc_date'];
            $proccesses[$i]['status'] = $row['status'];
            $proccesses[$i]['id_user'] = $row['id_user'];
            $proccesses[$i]['user_name'] = $row['user_name'];
            $i++;
        };
        echo json_encode($proccesses);
    }else{
        http_response_code(404);
    }

?>