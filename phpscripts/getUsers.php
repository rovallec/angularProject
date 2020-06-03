<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id_account);

$users = [];

$sql = "SELECT * FROM `users` WHERE `department` = '$id' AND `valid` = '1';";

if($result = mysqli_query($con, $sql)){
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idUser'] = $row['idUser'];
        $user[$i]['username'] = $row['username'];
        $user[$i]['user_name'] = $row['user_name'];
        $user[$i]['department'] = $row['department'];
        $user[$i]['valid'] = $row['valid'];
        $i++;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
