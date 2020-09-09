<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "classlist.csv" . '"');
require 'database.php';

$date = $_GET['date'];


$sql = "SELECT `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `profiles`.`tittle`, `profiles`.`day_of_birth`, `hires`.`nearsol_id`, `employees`.`hiring_date`, `employees`.`job`, `proc`.`motive`, `proc`.`kind`, `proc`.`reason`, `proc`.`rehireable`, `proc`.`valid_from`, `recriuter`.`user_name` AS `reciutment`, `ops`.`value` AS `operations`, `exp`.`company` AS `experience`, `accounts`.`name` AS `LOB`
FROM `profiles` LEFT JOIN `hires` ON `hires`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires` LEFT JOIN (SELECT * FROM hr_processes LEFT JOIN `terminations` ON `terminations`.`id_process` = `hr_processes`.`idhr_processes`WHERE `id_type` = 8) AS `proc` ON `proc`.`id_employee` = `employees`.`idemployees` LEFT JOIN (SELECT `user_name`, `id_profile` FROM `processes` LEFT JOIN `users` ON `users`.`idUser` = `processes`.`id_user` WHERE `name` = 'New Hire') 
AS `recriuter` ON `recriuter`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN (SELECT `id_profile`, `value` FROM `processes` LEFT JOIN `process_details` ON `process_details`.`id_process` = `processes`.`idprocesses` WHERE `processes`.`name` = 'Second Interview' AND `process_details`.`name` = 'Second Interview') AS `ops` ON `ops`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN (SELECT `company`, `id_profile` FROM `job_histories` LIMIT 1) AS `exp` ON `exp`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` WHERE `date` BETWEEN '2020-01-01' AND '2020-09-08'";

$output = fopen("php://output", "w");
fputcsv($output, array("No.", "Name", "Gender", "Birth Date", "Nearsol ID", "Hire Date", "Position", "Term. Motive", "Term. Kind", "Term. Reason", "Rehireable", "Term. Date", ""));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $i;
        $exportRow[1]=$row['first_name'] . " " . $row['second_name'] ." " . $row['first_lastname'] . " " . $row['second_lastname'];
        if($row['tittle'] == 'MR'){
            $exportRow[2] = 'Male';
        }else{
            $exportRow[2] = 'Female'
        }
        $exportRow[3] = $row['day_of_birth'];
        $exportRow[4] = $row['nearsol_id'];
        $exportRow[5] = $row['hiring_date'];
        $exportRow[6] = $row['job'];
        $exportRow[7] = $row['motive'];
        $exportRow[8] = $row['kind'];
        $exportRow[9] = $row['reason'];
        $exportRow[10] = $row['rehireable'];
        $exportRow[11] = $row['valid_from'];
        $exportRow[12] = $row['recriutment'];
        $exportRow[13] = $row['operations'];
        $exportRow[14] = $row['experience'];
        $exportRow[15] = $row['LOB'];
        for ($a=0; $a < 7; $a++) { 
            if($days[$a]==explode(",", $row['days_off'])[0] || $days[$a]==explode(",", $row['days_off'])[1]){
                $exportRow[7+$a] = "OFF";
            }else{
                $exportRow[7+$a] = explode(":",$row['start_time'])[0] . ":" . explode(":",$row['start_time'])[1] . "-" . explode(":", $row['end_time'])[0] . ":" . explode(":", $row['end_time'])[1];
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