<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$date = ($request->date);
$account = ($request->account);


$res = [];
$i = 0;

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_profile'] = $row['id_profile'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['id_hire'] = $row['id_hire'];
        $res[$i]['id_account'] = $row['id_account'];
        $res[$i]['reporter'] = $row['reporter'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['hiring_date'] = $row['hiring_date'];
        $res[$i]['job'] = $row['job'];
        $res[$i]['base_payment'] = $row['base_payment'];
        $res[$i]['productivity_payment'] = $row['productivity_payment'];
        $res[$i]['state'] = $row['state'];
        $res[$i]['id_user'] = $row['id_user'];
        $res[$i]['id_department'] = $row['id_department'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['account'] = $row['account'];
        $res[$i]['platform'] = $row['platform'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $i++;
    }
    echo(json_encode($res));
}
?>