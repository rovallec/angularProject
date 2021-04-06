<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "CargaProyeccionyActualizacion.csv" . '"');
require 'database.php';

$start = $_GET['start'];
$start_year = (date("Y")-1) . "-12-01";
$end = $_GET['end'];

$isr = [];

$monthly_mult = 0;

$today_date = new DateTime(date("Y-m-d"));

$ag_date = new DateTime((date("Y")) . "-12-01");
$bn_date = new DateTime((date("Y")) . "-07-01");

if(date("d", strtotime($end)) <= 15){
    $monthly_mult = 1;
}else{
    $monthly_mult = 0.5;
}

echo "\xEF\xBB\xBF";

$sql = "SELECT employees.idemployees, profiles.nit, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, coalesce(`cmp_base`, 0) AS `base`, coalesce(`cmp_productivity`,0) AS `productivity`,
coalesce(`crd`.`amnt`,0) AS `bonuses`,
'250.00' AS `decreto`, coalesce(`ot`,0) AS `over_time`, coalesce(`rise_amount`,0) AS `rises`, employees.hiring_date, coalesce(employees.indemnizations,0) AS `indemnization`, coalesce(employees.retentions,0) AS `retention`,
coalesce(`real_base`,0) AS `print_base`, coalesce(`real_productivity`,0) AS `print_productivity`, SUM(coalesce(`b_decreto`.`b_amt`,0)) AS `decreto_acumulado`, `trm`.`term_cat`, coalesce(`indemn`.`indemnization`,0) AS `indemn`,
coalesce(`total_igss`.`igss_deb`,0) AS `igss_payed`, coalesce(`ag`.`ag_amnt`,0) AS `aguinaldo`, coalesce(`b_14`.`b14_amnt`,0) AS `bono_14`
FROM employees
	INNER JOIN hires ON hires.idhires = employees.id_hire
	INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    LEFT JOIN (SELECT group_concat(new_salary) AS `rise_amount`, id_employee FROM hr_processes 
					INNER JOIN rises ON rises.id_process = hr_processes.idhr_processes WHERE hr_processes.id_type = 11 GROUP BY id_employee) AS `rise` ON `rise`.id_employee = employees.idemployees
    LEFT JOIN (SELECT avg(payments.base_complete) AS `cmp_base`, avg(payments.productivity_complete) AS `cmp_productivity`, SUM(payments.base) AS `real_base`, SUM(payments.productivity) AS `real_productivity`, SUM(ot) AS `ot`, id_employee FROM payments
					INNER JOIN periods ON periods.idperiods = payments.id_period AND periods.start BETWEEN '$start_year' AND '$end'
			   GROUP BY id_employee) AS `pay` ON `pay`.id_employee = employees.idemployees
    LEFT JOIN (SELECT SUM(credits.amount) AS `b_amt`, id_employee
               FROM credits INNER JOIN payments ON payments.idpayments = credits.id_payment
			   WHERE credits.type = 'Bonificacion Decreto'
               GROUP BY id_employee) AS `b_decreto` ON `b_decreto`.id_employee = employees.idemployees
	LEFT JOIN (SELECT SUM(credits.amount) AS `amnt`, id_employee
			    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
				WHERE credits.type NOT IN('Salario Base', 'Bonificacion Productividad', 'Bonificacion Decreto') 
					AND credits.type NOT LIKE '%Ajuste%' AND credits.type NOT LIKE '%Horas Extra Laboradas:%'  AND credits.type NOT LIKE '%Indemnizacion Periodo%'
                    AND credits.type NOT LIKE '%Aguinaldo Periodo%' AND credits.type NOT LIKE '%Bono 14 Periodo%' AND credits.type NOT LIKE '%Vacaciones Periodo%'
					AND credits.type NOT LIKE '%Horas De Asueto:%' GROUP BY id_employee) AS `crd` ON `crd`.id_employee = employees.idemployees
	LEFT JOIN (SELECT SUM(credits.amount) AS `b14_amnt`, id_employee
			    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
				WHERE credits.type LIKE '%Bono 14 Periodo%' GROUP BY id_employee) AS `b_14` ON `b_14`.id_employee = employees.idemployees
    LEFT JOIN (SELECT SUM(credits.amount) AS `ag_amnt`, id_employee
			    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
				WHERE credits.type LIKE '%Aguinaldo Periodo%' GROUP BY id_employee) AS `ag` ON `ag`.id_employee = employees.idemployees
	LEFT JOIN (SELECT SUM(credits.amount) AS `indemnization`, id_employee
			    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
				WHERE credits.type LIKE '%Indemnizacion: Periodo%'
				GROUP BY id_employee) AS `indemn` ON `indemn`.id_employee = employees.idemployees
	LEFT JOIN (SELECT SUM(debits.amount) AS `igss_deb`, id_employee
			    FROM debits INNER JOIN payments on payments.idpayments = debits.id_payment 
				WHERE debits.type LIKE 'Descuento IGSS'
				GROUP BY id_employee) AS `total_igss` ON `total_igss`.id_employee = employees.idemployees
	INNER JOIN (SELECT MIN(terminations.valid_from) AS `term_cat`, id_employee FROM hr_processes
			   INNER JOIN terminations ON terminations.id_process = hr_processes.idhr_processes
               WHERE hr_processes.id_type = 8 GROUP BY id_employee) AS `trm` ON `trm`.id_employee = employees.idemployees
WHERE active = 0 AND  `term_cat` BETWEEN '$start' AND '$end' GROUP BY idemployees;";

$output = fopen("php://output", "w");
fputcsv($output, array("NIT empleado", "Sueldos", "Horas Extras", "Bono Decreto 37-2001", "Otras Bonificaciones", "Comisiones", "Propinas", "Aguinaldo", "Bono Anual de trabajadores (14)", "Viáticos", "Gasto de representación", "Dietas", "Gratificaciones", "Remuneraciones", "Prestaciones IGSS", "Otros", "Indemnizaciones o pensiones por causa de muerte", "Indemnizaciónes por tiempo servido", "Remuneraciones de los diplomáticos", "Gastos de representación y viáticos comprobables", "Aguinaldo", "Bono Anual de trabajadores (14)", "Cuotas IGSS  y Otros planes de seguridad social"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $term_date = new DateTime($row['term_cat']);
        $isr[0] = str_replace("-", "",$row['nit']);
        $isr[1] = number_format($row['print_base'],2);
        $isr[2] = $row['over_time'];
        $isr[3] = number_format(($row['decreto_acumulado']),2);
        $isr[4] = number_format(($row['print_productivity'] + $row['bonuses']),2);
        $isr[5] = '0';
        $isr[6] = '0';
//////////////////////////////////////////////////AGUINALDO////////////////////////////////////////////////////////////
        $isr[7] = number_format($row['aguinaldo']);
//////////////////////////////////////////////////BONO 14//////////////////////////////////////////////////////////////
        $isr[8] = number_format($row['bono_14']);
        $isr[9] = '0';
        $isr[10] = '0';
        $isr[11] = '0';
        $isr[12] = '0';
        $isr[13] = '0';
        $isr[14] = '0';
        $isr[15] = '0';
        $isr[16] = '0';
        $isr[17] = $row['indemn'];
        $isr[18] = '0';
        $isr[19] = '0';
        $isr[20] = number_format($row['aguinaldo']);
        $isr[21] = number_format($row['bono_14']);
        $isr[22] = number_format($row['igss_payed'],2);
        fputcsv($output, $isr, ",");
    };
}else{
    http_response_code(404);
}
fclose($output);
?>