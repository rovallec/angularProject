<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$date = ($request->date);

$res[] = [];
$i = 0;

$sql1 = "SELECT * FROM `tk_imports` WHERE `date` $date;";
if($result = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idtk_import'] = $row['idtk_imports'];
        $res[$i]['date'] = $row['date'];
        $res[$i]['path'] = $row['path'];
        $i++;
    }
}else{
    echo(mysqli_error($con));
}
?>