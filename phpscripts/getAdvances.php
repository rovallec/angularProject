<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT * FROM `advances` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idadvances'] = $row['idadvances'];
        $user['id_process'] = $row['id_process'];
        $user['type'] = $row['type'];
        $user['description'] = $row['description'];
        $user['classification'] = $row['classification'];
        $user['amount'] = $row['amount'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
