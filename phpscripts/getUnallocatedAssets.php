<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res[] = [];
$i = 0;

$sql1 = "SELECT *, asset_type.name AS `type_name`, assets.status AS `st` FROM assets
        INNER JOIN asset_type ON asset_type.idasset_type = assets.id_type
        INNER JOIN asset_manufactures ON asset_manufactures.idasset_manufactures = assets.id_manufacture
        LEFT JOIN asset_movements ON asset_movements.idasset_movements = assets.idassets AND asset_movements.status = 1
        WHERE asset_movements.idasset_movements IS NULL;";
if($result = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idassets'] = $row['idassets'];
        $res[$i]['id_type'] = $row['id_type'];
        $res[$i]['id_manufacture'] = $row['id_manufacture'];
        $res[$i]['code'] = $row['code'];
        $res[$i]['cost'] = $row['cost'];
        $res[$i]['serial'] = $row['serial'];
        $res[$i]['status'] = $row['st'];
        $res[$i]['type_name'] = $row['type_name'];
        $res[$i]['brand'] = $row['brand'];
        $res[$i]['model'] = $row['model'];
        $i++;
    }
    echo(json_encode($res));
}else{
    echo(mysqli_error($con));
}
?>