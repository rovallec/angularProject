<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$start = ($request->start);
$end = ($request->end);
$account = ($request->account);

$user = [];
$i = 0;

$sql = "SELECT `app`.user_name as `approved`,  users.user_name, marginalizations.*, marginalization_details.*, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname,
hires.nearsol_id, employees.client_id FROM marginalization_details
LEFT JOIN attendences ON attendences.idattendences = marginalization_details.id_attendance
LEFT JOIN employees ON employees.idemployees = attendences.id_employee
LEFT JOIN hires ON hires.idhires = employees.id_hire
LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
LEFT JOIN marginalizations ON marginalizations.idmarginalizations = marginalization_details.idmarginalization_details
LEFT JOIN users ON users.idUser = marginalizations.id_user LEFT JOIN users AS `app`  on `app`.idUser = marginalizations.approved_by
WHERE marginalizations.date BETWEEN '$start' AND '$end' AND employees.id_account = $account;";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
    //Employees
    $user[$i]['nearsol_id'] = $row['nearsol_id'];
    $user[$i]['name'] = $row['first_name'] .  ' ' . $row['second_name'] . ' ' . $row['first_lastname'] . ' ' . $row['second_lastname'];
    //Master
    $user[$i]['id_user'] = $row['user_name'];
    $user[$i]['approved_by'] = $row['approved'];
    $user[$i]['date'] = $row['date'];
    $user[$i]['type'] = $row['type'];
    //Details
    $user[$i]['before'] = $row['before'];
    $user[$i]['after'] = $row['after'];
    $user[$i]['value'] = $row['value'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
    echo($sql);
}

?>

