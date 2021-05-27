<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "activeAnalysis.csv" . '"');
require 'database.php';

$i = 0;


$sql = "SELECT `contact_details`.`email`, `hires`.`nearsol_id`, `employees`.`client_id`, `exp`.`position`, `contact_details`.`primary_phone`, `emergency_details`.`e_first_name`, `emergency_details`.`e_first_lastname`, `emergency_details`.`phone`, `emergency_details`.`relationship`, `education_details`.`further_education`, `education_details`.`currently_studing`, `education_details`.`institution_name`, `contact_details`.`address`, `profiles`.`marital_status`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `profiles`.`tittle`, `profiles`.`day_of_birth`, `employees`.`hiring_date`, `employees`.`job`, `exp`.`company` AS `experience`, `accounts`.`name` AS `LOB`, `education_details`.`current_level` FROM `profiles` LEFT JOIN `hires` ON `hires`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires`
LEFT JOIN (SELECT `company`, `id_profile`, `position` FROM `job_histories` LIMIT 1) AS `exp` ON `exp`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `accounts` ON `accounts`.`idaccounts` = `employees`.`id_account` LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN emergency_details ON emergency_details.id_profile = profiles.idprofiles WHERE `employees`.`active` = 1;";
$output = fopen("php://output", "w");
fputcsv($output, array("No.", "Nearsol ID", "Client ID", "Name", "Gender", "Birth Date", "Hire Date", "Account", "Job Title",  "Last Job", "Current Education Level", "University", "Studying", "Institution", "Address", "Phone Number", "Emergency Contact", "Email"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $i;
        $exportRow[1] = $row['nearsol_id'];
        $exportRow[2] = $row['client_id'];
        $exportRow[3]=$row['first_name'] . " " . $row['second_name'] ." " . $row['first_lastname'] . " " . $row['second_lastname'];
        if($row['tittle'] == 'MR'){
            $exportRow[4] = 'Male';
        }else{
            $exportRow[4] = 'Female';
        }
        $exportRow[5] = $row['day_of_birth'];
        $exportRow[6] = $row['hiring_date'];
        $exportRow[7] = $row['LOB'];
        $exportRow[8] = $row['position'];
        $exportRow[9] = $row['experience'];
        $exportRow[10] = $row['current_level'];
        $exportRow[11] = $row['further_education'];
        $exportRow[12] = $row['currently_studing'];
        $exportRow[13] = $row['institution_name'];
        $exportRow[14] = $row['address'];
        $exportRow[15] = $row['primary_phone'];
        $exportRow[16] = $row['e_first_name'] . " " . $row['e_first_lastname'] . " " . $row['phone'];
        $exportRow[17] = $row['email'];
        fputcsv($output, $exportRow);
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>