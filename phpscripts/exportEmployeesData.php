<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "classlist.csv" . '"');
require 'database.php';

$exportRow = [];
$i = 0;

$sql = "SELECT users.*, employees.*, `term`.*, hires.*, profiles.*, accounts.name as `acc_name` FROM employees LEFT JOIN (SELECT * FROM terminations LEFT JOIN hr_processes ON hr_processes.idhr_processes = terminations.id_process) AS `term` ON `term`.id_employee = employees.idemployees LEFT JOIN hires ON hires.idhires = employees.id_hire LEFT JOIN payment_methods ON payment_methods.id_employee = employees.idemployees LEFT JOIN profiles on profiles.idprofiles = hires.id_profile LEFT JOIN users ON users.idUser = employees.reporter LEFT JOIN accounts ON accounts.idaccounts = employees.id_account WHERE employees.id_account in(SELECT idaccounts FROM accounts WHERE id_client != 2) AND `employees`.`job` = 'Representante de Servicio al Cliente';";

$output = fopen("php://output", "w");
fputcsv($output, array("Site", "Onsite Employee/Remote Employee", "Company Name", "Business Class", "Industry", "Department", "Work Category", "Employee Number", "Employee First Name", "Employee Last Name", "Employee Status", "Employment Status Reason", "Gender Code (M/F)", "Gross Pay (Annual)", "Org Hire Date", "Hire Date", "Termination Date", "Job Title", "Pay Group", "Pay Class", "Pay Type", "Supervisor Name"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[1]="Guatemala";
        $exportRow[2]=$row['platform'];
        $exportRow[3]="";
        $exportRow[4]="";
        $exportRow[5]="Call Center & BPO";
        $exportRow[6]=$row['acc_name'];
        $exportRow[7]="Operations";
        $exportRow[8]=$row['nearsol_id'];
        $exportRow[9]=$row['first_name'];
        $exportRow[10]=$row['first_lastname'];
        if($row['state'] == 'EMPLOYEE'){
            $exportRow[11]="Active";
        }else{
            $exportRow[11]="Inactive";
        }
        if($row['kind'] == "Despido"){
            $exportRow[12]="Dismissal";
        }else{
            $exportRow[12]="Quit";
        }
        if($row['gender'] == 'Masculino'){
            $exportRow[13]="Male";
        }else{
            $exportRow[13]="Female";
        }
        $exportRow[14]=((float)($row['base_payment'])+(float)($row['productivity_payment']))*12;
        $exportRow[15]=$row['hiring_date'];
        $exportRow[16]=$row['hiring_date'];
        $exportRow[17]=$row['date'];
        $exportRow[18]='Customer Service Agent';
        $exportRow[19]=$row['bank'];
        $exportRow[20]="FT w/benefits";
        $exportRow[21]="Salaried";
        $exportRow[22]=$row['user_name'];
        fputcsv($output, $exportRow);
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>