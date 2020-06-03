<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$filter = ($request->filter);
$value = ($request->filterValue);

$res = [];
$i = 0;
if($filter == 'between'){
    $sql = "SELECT * FROM `realtimetrack` WHERE `date` $value";
}else{
    if($value == "IS NULL"){
        $sql = "Select * from `realtimetrack` WHERE `$filter` $value;";
    }else{
        $sql = "Select * from `realtimetrack` WHERE `$filter` = '$value';";
    }
}
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['date'] = $row['date'];
        $res[$i]['first_name'] = $row['first_name'];
        $res[$i]['second_name'] = $row['second_name'];
        $res[$i]['first_lastname'] = $row['first_lastname'];
        $res[$i]['second_lastname'] = $row['second_lastname'];
        $res[$i]['id_profile'] = $row['id_profile'];
        $res[$i]['lastProcess'] = $row['lastprocess'];
        $res[$i]['firstInterview'] = $row['firstInterview'];
        $res[$i]['secondInterview'] = $row['secondInterview'];
        $res[$i]['lastprocessName'] = $row['lastprocessName'];
        $res[$i]['lastValue'] = $row['lastValue'];
        $res[$i]['Recruiter'] = $row['Recruiter'];
        $res[$i]['wave'] = $row['wave'];
        $res[$i]['account'] = $row['account'];
        $res[$i]['startingDate'] = $row['startingDate'];
        $res[$i]['candidate_status'] = $row['candidate_status'];
        $res[$i]['comment'] = $row['comment'];
        $i++;
    }
    echo(json_encode($res));
}
?>