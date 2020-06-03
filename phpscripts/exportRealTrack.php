<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "RealTimeTrakc.csv" . '"');
require 'database.php';

$filter = $_GET['filter'];
$value = $_GET['value'];

$proccesses = [];
$i = 0;



if($filter == 'between'){
    $val = explode("_", $value);
    $str = implode(" ", $val);
    $sql = "SELECT * FROM `realtimereport` WHERE `date` $str";
}else{
    if($value == "IS NULL"){
        $val = explode("_", $value);
        $str = implode(" ", $val);
        $sql = "Select * from `realtimereport` WHERE `$filter` $str;";
    }else{
        $sql = "Select * from `realtimereport` WHERE `$filter` = '$value';";
    }
}
$output = fopen("php://output", "w");
fputcsv($output, array("Date", "Last Process", "Last Status", "First Name", "Middle Name", "First Last Name","Second Last Name", "DPI", "Phone Number", "Email", "Source", "Post", "Referrer Name", "Where Do You Heare About Us?", "Recruiter", "1st Status", "2nd Status", "Account", "Wave", "Starting Date", "Status", "Comment"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        array_splice($row,0,1);
        array_splice($row,15,1);
        array_splice($row,15,1);
        fputcsv($output, $row, ",");
    };
}else{
    http_response_code(404);
}
fclose($output);
?>