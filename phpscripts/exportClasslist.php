<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "classlist.csv" . '"');
require 'database.php';

$wave = $_GET['wave'];

$exportRow = [];
$days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$i = 0;

$sql = "SELECT * FROM `classlist` WHERE `id_wave` = $wave";

$output = fopen("php://output", "w");
fputcsv($output, array("No.", "First Name", "Middle Name", "First Lastname", "Second Lastname", "DPI/ID", "Phone number", "SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $i;
        $exportRow[1]=$row['first_name'];
        $exportRow[2]=$row['second_name'];
        $exportRow[3]=$row['first_lastname'];
        $exportRow[4]=$row['second_lastname'];
        $exportRow[5]=$row['dpi'];
        $exportRow[6]=$row['primary_phone'];
        for ($a=0; $a < 7; $a++) { 
            if (strpos($row['days_off'], ',')) {
                if($days[$a]==explode(",", $row['days_off'])[0] || $days[$a]==explode(",", $row['days_off'])[1]){
                    $exportRow[7+$a] = "OFF";
                }else{
                    $exportRow[7+$a] = explode(":",$row['start_time'])[0] . ":" . explode(":",$row['start_time'])[1] . "-" . explode(":", $row['end_time'])[0] . ":" . explode(":", $row['end_time'])[1];
                }
            }
        }
        fputcsv($output, $exportRow);
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>