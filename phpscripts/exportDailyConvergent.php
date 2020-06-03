<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "dailyConvergent.csv" . '"');
require 'database.php';

$wave = $_GET['wave'];

$i = 1;

$sql = "SELECT * FROM `dailyconvergent` WHERE `id_wave` = '$wave'";
$output = fopen("php://output", "w");
fputcsv($output, array("No.", "First Name", "Middle Name", "First Last Name", "Second Last Name", "Recruiter", "Operations", "Hired Date", "DPI/ID", "Phone Number"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $row['id_wave'] = $i;
        fputcsv($output, $row, ",");
        $i = $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>