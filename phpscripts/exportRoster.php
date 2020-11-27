<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "roster.csv" . '"');
require 'database.php';

$account = $_GET['acc'];

$roster = [];
$i = 0;
$date = date("Y-m-d");

$sql = "SELECT * FROM employees LEFT JOIN hires ON hires.idhires = employees.id_hire LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile LEFT JOIN (SELECT * FROM hr_processes WHERE id_type = 8) AS `term` ON `term`.id_employee  = employees.idemployees  WHERE (active = 1 AND id_account = $account AND employees.hiring_date <= '$date') OR (`term`.date >= '$date' AND id_account = $account);";

$output = fopen("php://output", "w");
fputcsv($output, array("Client ID", "Date", "Name"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $roster[0] = $row['client_id'];
        $roster[1] = $date;
        $roster[2] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        fputcsv($output, $roster, ",");
    };
}else{
    http_response_code(404);
}
fclose($output);
?>