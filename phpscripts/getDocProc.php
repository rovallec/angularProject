<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocess);

$process = [];
$i = 0;

$sql = "SELECT `iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`, `active` FROM `documents` WHERE `id_process` = '{$id}'";

if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $process[$i]['iddocuments'] = $row['iddocuments'];
        $process[$i]['id_profile'] = $row['id_profile'];
        $process[$i]['id_process'] = $row['id_process'];
        $process[$i]['doc_type'] = $row['doc_type'];
        $process[$i]['doc_path'] = $row['doc_path'];
        $process[$i]['active'] = $row['active'];
        $i++;
    };
    echo json_encode($process);
}else{
    http_response_code(404);
}
?>