<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_role = ($request->id_role);
$id_profile = ($request->id_profile);

$proccesses = [];
$i = 0;


$sql = "select * from process_types WHERE `idprocess_types` > 7;";
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            $proccesses[$i]['idprocesses'] = $row['idprocesses'];
            $proccesses[$i]['name'] = $row['name'];
            $proccesses[$i]['descritpion'] = $row['description'];Aa
            $i++;
        };
        echo json_encode($proccesses);
    }else{
        http_response_code(404);
    }

?>