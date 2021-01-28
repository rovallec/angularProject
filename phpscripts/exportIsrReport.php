<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "CargaProyeccionyActualizacion.csv" . '"');
require 'database.php';

$start = $_GET['start'];
$end = $_GET['end'];

$isr = [];
$current_date = date("Y-m-d");
$today_date = new DateTime(date("Y-m-d"));
$ag_date = new DateTime((date("Y")) . "-12-01");
$bn_date = new DateTime((date("Y")) . "-07-01");

echo "\xEF\xBB\xBF";

$sql = "SELECT employees.idemployees, profiles.nit, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, coalesce(employees.base_payment, 0) AS `base`, coalesce((employees.productivity_payment-250),0) AS `productivity`,
'250.00' AS `decreto`, coalesce(`ot`,0) AS `over_time`, coalesce(`rise_amount`,0) AS `rises`, employees.hiring_date, coalesce(employees.indemnizations,0) AS `indemnization`, coalesce(employees.retentions,0) AS `retention`,
coalesce(`real_base`,0) AS `print_base`, coalesce(`real_productivity`,0) AS `print_productivity` FROM employees
	INNER JOIN hires ON hires.idhires = employees.id_hire
	INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    LEFT JOIN (SELECT group_concat(new_salary) AS `rise_amount`, id_employee FROM hr_processes 
					INNER JOIN rises ON rises.id_process = hr_processes.idhr_processes WHERE hr_processes.id_type = 11 GROUP BY id_employee) AS `rise` ON `rise`.id_employee = employees.idemployees
    LEFT JOIN (SELECT SUM(payments.base) AS `real_base`, SUM(payments.productivity) AS `real_productivity`, SUM(ot) AS `ot`, id_employee FROM payments
					INNER JOIN periods ON periods.idperiods = payments.id_period AND periods.start BETWEEN '$start' AND '$end'
			   GROUP BY id_employee) AS `pay` ON `pay`.id_employee = employees.idemployees
WHERE active = 1 GROUP BY idemployees;";

$output = fopen("php://output", "w");
fputcsv($output, array("NIT empleado", "Sueldos", "Horas Extras", "Bono Decreto 37-2001", "Otras Bonificaciones", "Comisiones", "Propinas", "Aguinaldo", "Bono Anual de trabajadores (14)", "Viáticos", "Gasto de representación", "Dietas", "Gratificaciones", "Remuneraciones", "Prestaciones IGSS", "Otros", "Indemnizaciones o pensiones por causa de muerte", "Indemnizaciónes por tiempo servido", "Remuneraciones de los diplomáticos", "Gastos de representación y viáticos comprobables", "Aguinaldo", "Bono Anual de trabajadores (14)", "Cuotas IGSS  y Otros planes de seguridad social"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $isr[0] = str_replace("-", "",$row['nit']);
        $isr[1] = number_format(($row['base'] * (13 - date('m')) + $row['print_base']),2);
        $isr[2] = $row['over_time'];
        $isr[3] = 250*12;
        $isr[4] = number_format(($row['productivity'] * (13 - date('m')) + $row['print_productivity']),2); // + productividad bono 14 y aguinaldo + bonificaciones;
        $isr[5] = '0';
        $isr[6] = '0';
//////////////////////////////////////////////////AGUINALDO//////////////////////////////////////////////////////////////
        if(date($row['hiring_date']) <= date((date("Y")-1) . "-12-01")){
            $a_date = new DateTime((date("Y")-1) . "-12-01");
            $a_diff = $a_date->diff($ag_date);
            $a_days = $a_diff->format("%a");
        }else{
            $a_date = new DateTime($row['hiring_date']);
            $a_diff = $ag_date->diff($a_date);
            $a_days = $a_diff->format("%a");
        };
        $isr[7] = number_format(($row['base'] + $row['productivity']) * ($a_days/365),2);
//////////////////////////////////////////////////BONO 14//////////////////////////////////////////////////////////////
        if(date($row['hiring_date']) <= date((date("Y")-1) . "-07-01")){
            $b_date = new DateTime((date("Y")-1) . "-07-01");
            $b_diff = $bn_date->diff($b_date);
            $b_days = $b_diff->format("%a");
        }else{
            $b_date = new DateTime($row['hiring_date']);
            $b_diff = $bn_date->diff($b_date);
            $b_days = $b_diff->format("%a");
        };
        $isr[8] = number_format(($row['base'] + $row['productivity']) * ($b_days/365),2);
        $isr[9] = '0';
        $isr[10] = '0';
        $isr[11] = '0';
        $isr[12] = '0';
        $isr[13] = '0';
        $isr[14] = '0';
        $isr[15] = $row['indemnization'];
        $isr[16] = '0';
        $isr[17] = $row['indemnization'];
        $isr[18] = '0';
        $isr[19] = '0';
        $isr[20] = number_format(($row['base']) * ($a_days/365),2);
        $isr[21] = number_format(($row['productivity'] * (13 - date('m'))),2);
        $isr[22] = number_format(($row['base'] * (13 - date('m')) + $row['print_base'] + $row['over_time'])*0.0483,2);
        fputcsv($output, $isr, ",");
    };
}else{
    http_response_code(404);
}
fclose($output);
?>