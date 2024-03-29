<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `irtra_requests` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idprocess'] = $row['id_process'];
        $user['idirtra_requests'] = $row['id_process'];
        $user['type'] = $row['type'];
        $user['spouse_name'] = $row['spouse_name'];
        $user['spouse_lastname'] = $row['spouse_lastname'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>