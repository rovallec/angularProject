<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "activeAnalysis.csv" . '"');
require 'database.php';

$i = 0;


$sql = "SELECT `education_details`.`further_education`, `education_details`.`currently_studing`, `education_details`.`institution_name`, `contact_details`.`address`, `profiles`.`marital_status`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `profiles`.`tittle`, `profiles`.`day_of_birth`, `employees`.`hiring_date`, `employees`.`job`, `exp`.`company` AS `experience`, `accounts`.`name` AS `LOB`, `education_details`.`current_level` FROM `profiles` LEFT JOIN `hires` ON `hires`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires`
LEFT JOIN (SELECT `company`, `id_profile` FROM `job_histories` LIMIT 1) AS `exp` ON `exp`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` WHERE `employees`.`active` = 1;";
$output = fopen("php://output", "w");
fputcsv($output, array("No.", "Name", "Gender", "Birth Date", "Nearsol ID", "Hire Date", "Position", "Account",  "Last Job", "Current Education Level", "University", "Studying", "Institution", "Address"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $i;
        $exportRow[1]=$row['first_name'] . " " . $row['second_name'] ." " . $row['first_lastname'] . " " . $row['second_lastname'];
        if($row['tittle'] == 'MR'){
            $exportRow[2] = 'Male';
        }else{
            $exportRow[2] = 'Female';
        }
        $exportRow[3] = $row['day_of_birth'];
        $exportRow[5] = $row['hiring_date'];
        $exportRow[7] = $row['LOB'];
        $exportRow[8] = $row['experience'];
        $exportRow[9] = $row['current_level'];
        $exportRow[10] = $row['further_education'];
        $exportRow[11] = $row['currently_studing'];
        $exportRow[12] = $row['institution_name'];
        $exportRow[13] = $row['address'];

        fputcsv($output, $exportRow);
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>