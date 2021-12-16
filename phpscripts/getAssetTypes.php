<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res[] = [];
$i = 0;

$sql1 = "SELECT idasset_type, name, description, COUNT(idassets) AS `count` FROM asset_type
        LEFT JOIN assets ON assets.id_type = asset_type.idasset_type
        GROUP BY idasset_type;";
if($result = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idasset_type'] = $row['idasset_type'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['description'] = $row['description'];
        $res[$i]['count'] = $row['count'];
        $i++;
    }
    echo(json_encode($res));
}else{
    echo(mysqli_error($con));
}
?>