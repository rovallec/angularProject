<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$proccesses = [];
$i = 0;


$sql = "select * from process_types WHERE `idprocess_types` > 7 AND id_role != 12;";
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            $proccesses[$i]['idprocesses'] = $row['idprocess_types'];
            $proccesses[$i]['name'] = $row['name'];
            $proccesses[$i]['descritpion'] = $row['description'];
            $i++;
        };
        echo json_encode($proccesses);
    }else{
        http_response_code(404);
    }

?>