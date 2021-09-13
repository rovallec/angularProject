<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$user = [];

$sql = "SELECT * FROM `roster_times`;";

if($result = mysqli_query($con, $sql)){
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $user[$i]['idroster_times'] = $row['idroster_times'];
        $user[$i]['start'] = $row['start'];
        $user[$i]['end'] = $row['end'];
        $user[$i]['fixed'] = $row['fixed'];
        $i++;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
