<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

/* por el momento no se le envía ningún parámetro.
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);
*/


$user = [];

$sql = "SELECT idUser, username, signature FROM users WHERE id_role IN(6,7) AND valid = 1;";

if($result = mysqli_query($con, $sql)){
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idUser'] = $row['idUser'];
        $user[$i]['username'] = $row['username'];
        $user[$i]['signature'] = $row['signature'];
        $i++;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>