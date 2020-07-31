<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `call_tracks` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idcall_tracks'] = $row['idcall_tracks'];
        $user['id_process'] = $row['id_process'];
        $user['type'] = $row['type'];
        $user['reason'] = $row['reason'];
        $user['channel'] = $row['channel'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
