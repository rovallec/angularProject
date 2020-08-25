<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$idprocesses = ($request->idprocesses);
$user = [];

$sql = "SELECT * FROM `judicials` WHERE `id_process` = '$idprocesses';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idjudicials'] = $row['idjudicials'];
        $user['id_process'] = $row['id_process'];
        $user['amount'] = $row['amount'];
        $user['max'] = $row['max'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
