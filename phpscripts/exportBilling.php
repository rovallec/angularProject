<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "billingDetails.csv" . '"');
require 'database.php';



$i = 0;
$period = $_GET['period'];
$id_1 = explode(",",$period)[0];
$id_2 = explode(",",$period)[1];
$netsuitclient = $_GET['netsuit'];
$rowExport = [];
echo "\xEF\xBB\xBF";
$sql = "SELECT
clientNetSuite,
name,
nearsol_id,
client_id,
`employee name`,
ROUND(SUM(`base_pay`),2),
ROUND(SUM(`productivity`),2),
ROUND(SUM(`discounted_days`),2),
ROUND(SUM(`discounted_senths`),2),
ROUND(SUM(`hours`),2),
ROUND(SUM(`wage_deductions`),2),
ROUND(SUM(`incentive_deductions`),2),
ROUND(SUM(`base`),2),
ROUND(SUM(`productivity`),2),
ROUND(SUM(`ot_hours`),2),
ROUND(SUM(`ot`),2),
ROUND(SUM(`holidays_hours`),2),
ROUND(SUM(`holidays`),2),
ROUND(SUM(`bonuses_amount`),2),
ROUND(SUM(`trasure_amount`),2),
ROUND(SUM(`adjustment`),2),
ROUND(SUM(`total_income`),2),
ROUND(SUM(`car_amount`),2),
ROUND(SUM(`motorcycle_amount`),2),
ROUND(SUM(`igss_amount`),2),
ROUND(SUM(`isr_amount`),2),
ROUND(SUM(`headsets_amount`),2),
ROUND(SUM(`total_deductions`),2),
ROUND(SUM(`total_payment`),2),
ROUND(SUM(`base_aguinaldo`),2),
ROUND(SUM(`base_bono14`),2),
ROUND(SUM(`base_vacaciones`),2),
ROUND(SUM(`productivity_aguinaldo`),2),
ROUND(SUM(`productivity_bono14`),2),
ROUND(SUM(`productivity_vacaciones`),2),
ROUND(SUM(`base_indemnizacion`),2),
ROUND(SUM(`employeer_igss`),2),
ROUND(SUM(`health`),2),
SUM(`PARKING`),
SUM(`BUS`),
ROUND(SUM(`total_cost`),2)
FROM
(
SELECT
payments.idpayments,
clientNetSuite,
accounts.name,
hires.nearsol_id,
employees.client_id,
CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee name`,
ROUND(IF(employees.job_type = 1, 0, employees.base_payment),2) AS `base_pay`,
ROUND(IF(employees.job_type = 1, 0, employees.productivity_payment),2) AS `productivity_pay`,
ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.discounted_days,2)),2) AS `discounted_days`,
ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.seventh,2)),2) AS `discounted_senths`,
ROUND(IF(employees.job_type = 1, 0, payroll_values. discounted_hours),2) AS `hours`,
ROUND(IF(employees.job_type = 1, 0, ROUND(payments.base - ROUND((payments.base_complete/2),2), 2)),2) AS `wage_deductions`,
ROUND(IF(employees.job_type = 1, 0, ROUND(payments.productivity - ROUND(payments.productivity_complete/2,2), 2)),2) AS `incentive_deductions`,
ROUND(IF(employees.job_type = 1, 0, payments.base),2) AS `base`,
ROUND(IF(employees.job_type = 1, 0, payments.productivity),2) AS `productivity`,
ROUND(payments.ot_hours,2) AS `ot_hours`,
ROUND(payments.ot,2) AS `ot`,
ROUND(payments.holidays_hours,2) AS `holidays_hours`,
ROUND(payments.holidays,2) AS `holidays`,
`bonuses`.`bonuses_amount`,
`treasure_hunt`.`trasure_amount`,
`adjustments_positive`.`adjustment`,
ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, employees.productivity_payment),0) + coalesce(IF(employees.job_type = 1, 0, employees.base_payment),0),2) AS `total_income`,
`car_parking`.`car_amount`,
`motorcycle_parking`.`motorcycle_amount`,
`igss`.`igss_amount`,
`isr`.`isr_amount`,
`headset`.`headsets_amount`,
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2) AS `total_deductions`,
ROUND((ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, employees.productivity_payment),0) + coalesce(IF(employees.job_type = 1, 0, employees.base_payment),0),2)) -
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2) 
) AS `total_payment`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`) AS `base_aguinaldo`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`) AS `base_bono14`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`) AS `base_vacaciones`,
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`) AS `productivity_aguinaldo`,
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_bono14`) AS `productivity_bono14`,
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_vacaciones`) AS `productivity_vacaciones`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`) AS `base_indemnizacion`,
ROUND((IF(employees.job_type = 1, 0, payments.base)+
IF(employees.job_type = 1, 0, payments.productivity)+
ROUND(payments.ot,2)+
ROUND(payments.holidays)) * 0.01267, 2) AS `employeer_igss`,
198.24 AS `health`,
0 AS `PARKING`,
0 AS `BUS`,
ROUND(IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)+
ROUND((IF(employees.job_type = 1, 0, payments.base)+
IF(employees.job_type = 1, 0, payments.productivity)+
ROUND(payments.ot,2)+
ROUND(payments.holidays)) * 0.01267, 2),2) AS `total_reserves_and_fees`,
ROUND(ROUND(IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)+
ROUND((IF(employees.job_type = 1, 0, payments.base)+
IF(employees.job_type = 1, 0, payments.productivity)+
ROUND(payments.ot,2)+
ROUND(payments.holidays)) * 0.01267, 2),2)+
ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, employees.productivity_payment),0) + coalesce(IF(employees.job_type = 1, 0, employees.base_payment),0), 2) 
 + 198.24,2) AS `total_cost`
FROM payments
INNER JOIN employees ON employees.idemployees = payments.id_employee
INNER JOIN hires ON hires.idhires = employees.id_hire
INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
INNER JOIN payroll_values ON payroll_values.id_payment = payments.idpayments
INNER JOIN accounts ON accounts.idaccounts = payments.id_account_py
LEFT JOIN (
			SELECT 
            coalesce(ROUND(SUM(credits.amount),2),0) AS `bonuses_amount`,
            credits.id_payment 
            FROM credits
            WHERE (
					credits.type != 'Salario Base'
                    AND credits.type != 'Bonificacion Productividad'
                    AND credits.type != 'Treasure Hunt'
                    AND credits.type NOT LIKE '%Nearsol TK%'
                    AND credits.type NOT LIKE '%Horas Extra%'
                    AND credits.type NOT LIKE '%Horas de Asueto%'
                    AND credits.type NOT LIKE '%Ajuste%'
				   )
            GROUP BY id_payment
		  ) AS `bonuses` ON `bonuses`.id_payment = payments.idpayments
LEFT JOIN (
				SELECT
                coalesce(credits.amount,0) AS `trasure_amount`,
                id_payment
                FROM credits
                WHERE credits.type = 'Treasure Hunt'
			) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments
LEFT JOIN (
				SELECT
                coalesce(ROUND(SUM(credits.amount),2),0) AS `adjustment`,
                id_payment
                FROM credits
                WHERE credits.type LIKE '%Ajuste%' AND credits.amount > 0
                GROUP BY id_payment
			) AS `adjustments_positive` ON `adjustments_positive`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `car_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%car parking%'
            GROUP BY id_payment
		   ) AS `car_parking` ON `car_parking`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `motorcycle_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%motorcycle parking%'
            GROUP BY id_payment
		   ) AS `motorcycle_parking` ON `motorcycle_parking`.id_payment = payments.idpayments
INNER JOIN (
			SELECT
            IF(employees.job_type = 1, ROUND(debits.amount - ROUND(payments.base*0.0483,2),2), debits.amount) AS `igss_amount`,
            id_payment
            FROM
            debits
            INNER JOIN payments ON payments.idpayments = debits.id_payment
            INNER JOIN employees ON employees.idemployees = payments.id_employee
            WHERE debits.type = 'Descuento IGSS' AND (id_period = $id_1)
		  ) AS `igss` ON `igss`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `headsets_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%headset%'
            GROUP BY id_payment
		   ) AS `headset` ON `headset`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `isr_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%isr%'
            GROUP BY id_payment
		   ) AS `isr` ON `isr`.id_payment = payments.idpayments
INNER JOIN (
			SELECT
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2),2)
			AS `amount_base_aguinaldo`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2),2) AS `amount_base_indemnizacion`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2),2) AS `amount_base_bono14`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2),2) AS `amount_base_vacaciones`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2),2) AS `amount_productivity_aguinaldo`,
			ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2),2) AS `amount_productivity_bono14`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2),2) AS `amount_productivity_vacaciones`,
            pay.idpayments
			FROM payments pay
			INNER JOIN employees e ON (e.idemployees = pay.id_employee)
			INNER JOIN periods p ON (p.idperiods = pay.id_period)
			INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
			LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
						INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
						WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
			WHERE pay.id_period $id_1
			) AS `severances` ON `severances`.idpayments = payments.idpayments
WHERE (payments.id_period $id_1) and clientNetSuite = $netsuitclient
UNION
SELECT
payments.idpayments,
clientNetSuite,
accounts.name,
hires.nearsol_id,
employees.client_id,
CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee name`,
ROUND(IF(employees.job_type = 1, 0, employees.base_payment),2) AS `base_pay`,
ROUND(IF(employees.job_type = 1, 0, employees.productivity_payment),2) AS `productivity_pay`,
ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.discounted_days,2)),2) AS `discounted_days`,
ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.seventh,2)),2) AS `discounted_senths`,
ROUND(IF(employees.job_type = 1, 0, payroll_values. discounted_hours),2) AS `hours`,
ROUND(IF(employees.job_type = 1, 0, ROUND(payments.base - ROUND((payments.base_complete/2),2), 2)),2) AS `wage_deductions`,
ROUND(IF(employees.job_type = 1, 0, ROUND(payments.productivity - ROUND(payments.productivity_complete/2,2), 2)),2) AS `incentive_deductions`,
ROUND(IF(employees.job_type = 1, 0, payments.base),2) AS `base`,
ROUND(IF(employees.job_type = 1, 0, payments.productivity),2) AS `productivity`,
ROUND(payments.ot_hours,2) AS `ot_hours`,
ROUND(payments.ot,2) AS `ot`,
ROUND(payments.holidays_hours,2) AS `holidays_hours`,
ROUND(payments.holidays,2) AS `holidays`,
`bonuses`.`bonuses_amount`,
`treasure_hunt`.`trasure_amount`,
`adjustments_positive`.`adjustment`,
ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, employees.productivity_payment),0) + coalesce(IF(employees.job_type = 1, 0, employees.base_payment),0),2) AS `total_income`,
`car_parking`.`car_amount`,
`motorcycle_parking`.`motorcycle_amount`,
`igss`.`igss_amount`,
`isr`.`isr_amount`,
`headset`.`headsets_amount`,
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2) AS `total_deductions`,
ROUND((ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, employees.productivity_payment),0) + coalesce(IF(employees.job_type = 1, 0, employees.base_payment),0),2)) -
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2) 
) AS `total_payment`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`) AS `base_aguinaldo`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`) AS `base_bono14`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`) AS `base_vacaciones`,
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`) AS `productivity_aguinaldo`,
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_bono14`) AS `productivity_bono14`,
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_vacaciones`) AS `productivity_vacaciones`,
IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`) AS `base_indemnizacion`,
ROUND((IF(employees.job_type = 1, 0, payments.base)+
IF(employees.job_type = 1, 0, payments.productivity)+
ROUND(payments.ot,2)+
ROUND(payments.holidays)) * 0.01267, 2) AS `employeer_igss`,
198.24 AS `health`,
0 AS `PARKING`,
0 AS `BUS`,
ROUND(IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)+
ROUND((IF(employees.job_type = 1, 0, payments.base)+
IF(employees.job_type = 1, 0, payments.productivity)+
ROUND(payments.ot,2)+
ROUND(payments.holidays)) * 0.01267, 2),2) AS `total_reserves_and_fees`,
ROUND(ROUND(IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type = 1, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)+
ROUND((IF(employees.job_type = 1, 0, payments.base)+
IF(employees.job_type = 1, 0, payments.productivity)+
ROUND(payments.ot,2)+
ROUND(payments.holidays)) * 0.01267, 2),2)+
ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, employees.productivity_payment),0) + coalesce(IF(employees.job_type = 1, 0, employees.base_payment),0), 2) 
 + 198.24,2) AS `total_cost`
FROM payments
INNER JOIN employees ON employees.idemployees = payments.id_employee
INNER JOIN hires ON hires.idhires = employees.id_hire
INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
INNER JOIN payroll_values ON payroll_values.id_payment = payments.idpayments
INNER JOIN accounts ON accounts.idaccounts = payments.id_account_py
LEFT JOIN (
			SELECT 
            coalesce(ROUND(SUM(credits.amount),2),0) AS `bonuses_amount`,
            credits.id_payment 
            FROM credits
            WHERE (
					credits.type != 'Salario Base'
                    AND credits.type != 'Bonificacion Productividad'
                    AND credits.type != 'Treasure Hunt'
                    AND credits.type NOT LIKE '%Nearsol TK%'
                    AND credits.type NOT LIKE '%Horas Extra%'
                    AND credits.type NOT LIKE '%Horas de Asueto%'
                    AND credits.type NOT LIKE '%Ajuste%'
				   )
            GROUP BY id_payment
		  ) AS `bonuses` ON `bonuses`.id_payment = payments.idpayments
LEFT JOIN (
				SELECT
                coalesce(credits.amount,0) AS `trasure_amount`,
                id_payment
                FROM credits
                WHERE credits.type = 'Treasure Hunt'
			) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments
LEFT JOIN (
				SELECT
                coalesce(ROUND(SUM(credits.amount),2),0) AS `adjustment`,
                id_payment
                FROM credits
                WHERE credits.type LIKE '%Ajuste%' AND credits.amount > 0
                GROUP BY id_payment
			) AS `adjustments_positive` ON `adjustments_positive`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `car_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%car parking%'
            GROUP BY id_payment
		   ) AS `car_parking` ON `car_parking`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `motorcycle_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%motorcycle parking%'
            GROUP BY id_payment
		   ) AS `motorcycle_parking` ON `motorcycle_parking`.id_payment = payments.idpayments
INNER JOIN (
			SELECT
            IF(employees.job_type = 1, ROUND(debits.amount - ROUND(payments.base*0.0483,2),2), debits.amount) AS `igss_amount`,
            id_payment
            FROM
            debits
            INNER JOIN payments ON payments.idpayments = debits.id_payment
            INNER JOIN employees ON employees.idemployees = payments.id_employee
            WHERE debits.type = 'Descuento IGSS' AND (id_period = $id_2)
		  ) AS `igss` ON `igss`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `headsets_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%headset%'
            GROUP BY id_payment
		   ) AS `headset` ON `headset`.id_payment = payments.idpayments
LEFT JOIN (
			SELECT
            coalesce(ROUND(SUM(debits.amount),2),0) AS `isr_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%isr%'
            GROUP BY id_payment
		   ) AS `isr` ON `isr`.id_payment = payments.idpayments
INNER JOIN (
			SELECT
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2),2)
			AS `amount_base_aguinaldo`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2),2) AS `amount_base_indemnizacion`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2),2) AS `amount_base_bono14`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2),2) AS `amount_base_vacaciones`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2),2) AS `amount_productivity_aguinaldo`,
			ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2),2) AS `amount_productivity_bono14`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2),2) AS `amount_productivity_vacaciones`,
            pay.idpayments
			FROM payments pay
			INNER JOIN employees e ON (e.idemployees = pay.id_employee)
			INNER JOIN periods p ON (p.idperiods = pay.id_period)
			INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
			LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
						INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
						WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
			WHERE pay.id_period $id_2
			) AS `severances` ON `severances`.idpayments = payments.idpayments
WHERE (payments.id_period $id_2) and clientNetSuite = $netsuitclient
) AS `tmp`
GROUP BY idpayments,clientNetSuite,
name,
nearsol_id,
client_id,
`employee name`;";

$output = fopen("php://output", "w");
fputcsv($output, array('Code','Avaya','Name','Account','Minimum Wage','Incentive','Days discounted','7th deduction','Discounted hours','Minimum Wage Deductions','Incentive Deductions','Minimum Wage with deductions','Incentive with deductions','Overtime (hours)','Overtime (Q)','Holiday (hours)','Holiday (Q)','Bonuses','Treasure Hunt','Adjustments','Total income','Bus','Parking (Car)','Parking Motorcycle / bicycle','IGSS','ISR','Equipment','Total Deductions','Total Payment','BONUS 13','BONUS 13 BONIF','BONUS 14 ','BONUS 14 BONIF','VACATION RESERVES','VACATION RESERVES BONIF','SEVERANCE RESERVES','EMPLOYER IGSS','HEALTH INSURANCE','PARKING','BUS','TOTAL RESERVES AND FEES','TOTAL COST',));
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            fputcsv($output, $row, ",");
            $i++;
        };
    }else{
        echo($sql);
    }
fclose($output);
?>