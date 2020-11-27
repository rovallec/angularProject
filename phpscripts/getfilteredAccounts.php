<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);

$res = [];
$i = 0;

$sql1="SELECT * FROM `accounts` WHERE idaccounts = $id";

if($result2 = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result2)){
        $id_client = $row['id_client'];
    }
    $sql = "Select * from `accounts` WHERE id_client = $id_client OR id_client = 2;";
    if($request = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($request)){
            $res[$i]['idaccounts'] = $row['idaccounts'];
            $res[$i]['name'] = $row['name'];
            $i++;
        }
        echo(json_encode($res));
    }
}
?>