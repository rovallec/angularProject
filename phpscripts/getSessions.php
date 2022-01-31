<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->iduser);

$term = [];
$rss = '';
$sql = "SELECT * FROM users WHERE idUser = $id";
if($result = mysqli_query($con, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $authUser['iduser'] = $row['idUser'];
			$authUser['username'] = $row['username'];
			$authUser['department'] = $row['department'];
			$authUser['user_name'] = $row['user_name'];
			$authUser['valid'] = $row['valid'];
			$authUser['id_role'] = $row['id_role'];
			$authUser['signature'] = $row['signature'];
        }
        echo(json_encode($term));
    }else{
        $rss = '0';
        echo(json_encode($rss));
    }
}
?>