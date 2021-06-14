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
if($netsuitclient <= 6){
    $sql = "SELECT
    client_id AS `1`,
    `employee name` AS `2`,
    name AS `3`,
    nearsol_id AS `4`,
    ROUND(SUM(coalesce(`base_pay`,0)),2)/coalesce(COUNT(`base_pay`),1) AS `5`,
    ROUND(SUM(coalesce(`productivity_pay`,0)),2)/coalesce(COUNT(`base_pay`),1)  AS `6`,
    ROUND(SUM(coalesce(`discounted_days`,0)),2)  AS `7`,
    ROUND(SUM(coalesce(`discounted_senths`,0)),2)  AS `8`,
    ROUND(SUM(coalesce(`hours`,0)),2)  AS `9`,
    ROUND(SUM(coalesce(`wage_deductions`,0)),2)  AS `10`,
    ROUND(SUM(coalesce(`incentive_deductions`,0)),2) - IF(SUM(coalesce(`job_type`,0)) = 0, (250 - ROUND(SUM(coalesce(coalesce(`decreto_amount`,0),0)),2)), 0)  AS `11`,
    ROUND(SUM(coalesce(`base`,0)),2)  AS `12`,
    ROUND(SUM(coalesce(`productivity`,0)),2) + ROUND(SUM(coalesce(coalesce(`decreto_amount`,0),0)),2)  AS `13`,
    ROUND(SUM(coalesce(`ot_hours`,0)),2)  AS `14`,
    ROUND(SUM(coalesce(`ot`,0)),2)  AS `15`,
    ROUND(SUM(coalesce(`holidays_hours`,0)),2)  AS `16`,
    ROUND(SUM(coalesce(`holidays`,0)),2)  AS `17`,
    ROUND(SUM(coalesce(`bonuses_amount`,0)),2)  AS `18`,
    ROUND(SUM(coalesce(`trasure_amount`,0)),2)  AS `19`,
    ROUND(SUM(coalesce(`adjustment`,0)),2)  AS `20`,
	ROUND(coalesce(
        coalesce(ROUND(SUM(coalesce(`base`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`productivity`,0)),2) + ROUND(SUM(coalesce(coalesce(`decreto_amount`,0),0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`ot`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`holidays`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`bonuses_amount`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`trasure_amount`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`adjustment`,0)),2),0),0)
    ,2)  AS `21`,
    
    ROUND(SUM(coalesce(`bus_amount`,0)),2)  AS `22`,
    ROUND(SUM(coalesce(`car_amount`,0)),2)  AS `23`,
    ROUND(SUM(coalesce(`motorcycle_amount`,0)),2)  AS `24`,
    ROUND(SUM(coalesce(`igss_amount`,0)),2)  AS `25`,
    ROUND(SUM(coalesce(`isr_amount`,0)),2)  AS `26`,
    ROUND(SUM(coalesce(`headsets_amount`,0)),2)  AS `27`,
	ROUND(
		coalesce(ROUND(SUM(coalesce(`bus_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`car_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`motorcycle_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`igss_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`isr_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`headsets_amount`,0)),2),0),
    2)  AS `28`,
	ROUND(coalesce(
        coalesce(ROUND(SUM(coalesce(`base`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`productivity`,0)),2) + ROUND(SUM(coalesce(coalesce(`decreto_amount`,0),0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`ot`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`holidays`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`bonuses_amount`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`trasure_amount`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`adjustment`,0)),2),0),0)
    ,2) - 	ROUND(
		coalesce(ROUND(SUM(coalesce(`bus_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`car_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`motorcycle_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`igss_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`isr_amount`,0)),2),0)+
		coalesce(ROUND(SUM(coalesce(`headsets_amount`,0)),2),0),
    2) AS `29`,
    ROUND(SUM(coalesce(`base_aguinaldo`,0)),2) AS `30`,
    ROUND(SUM(coalesce(`productivity_aguinaldo`,0)),2) AS `31`,
    ROUND(SUM(coalesce(`base_bono14`,0)),2) AS `32`,
    ROUND(SUM(coalesce(`productivity_bono14`,0)),2) AS `33`,
    ROUND(SUM(coalesce(`base_vacaciones`,0)),2) AS `34`,
    ROUND(SUM(coalesce(`productivity_vacaciones`,0)),2) AS `35`,
    ROUND(SUM(coalesce(`base_indemnizacion`,0)),2) AS `36`,
    ROUND(SUM(coalesce(`employeer_igss`,0)),2) AS `37`,
    ROUND(SUM(coalesce(`health`,0)),2) AS `38`,
    SUM(coalesce(`PARKING`,0)) AS `39`,
    SUM(coalesce(`BUS`,0)) AS `40`,
    ROUND(
        ROUND(SUM(coalesce(`base_aguinaldo`,0)),2)+
		ROUND(SUM(coalesce(`productivity_aguinaldo`,0)),2)+
		ROUND(SUM(coalesce(`base_bono14`,0)),2)+
		ROUND(SUM(coalesce(`productivity_bono14`,0)),2)+
		ROUND(SUM(coalesce(`base_vacaciones`,0)),2)+
		ROUND(SUM(coalesce(`productivity_vacaciones`,0)),2)+
		ROUND(SUM(coalesce(`base_indemnizacion`,0)),2)+
		ROUND(SUM(coalesce(`employeer_igss`,0)),2)
    ,2) AS `41`,
    ROUND(
    	ROUND(coalesce(
        coalesce(ROUND(SUM(coalesce(`base`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`productivity`,0)),2) + ROUND(SUM(coalesce(coalesce(`decreto_amount`,0),0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`ot`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`holidays`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`bonuses_amount`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`trasure_amount`,0)),2),0) +
        coalesce(ROUND(SUM(coalesce(`adjustment`,0)),2),0),0)
    ,2) +     ROUND(
        ROUND(SUM(coalesce(`base_aguinaldo`,0)),2)+
		ROUND(SUM(coalesce(`productivity_aguinaldo`,0)),2)+
		ROUND(SUM(coalesce(`base_bono14`,0)),2)+
		ROUND(SUM(coalesce(`productivity_bono14`,0)),2)+
		ROUND(SUM(coalesce(`base_vacaciones`,0)),2)+
		ROUND(SUM(coalesce(`productivity_vacaciones`,0)),2)+
		ROUND(SUM(coalesce(`base_indemnizacion`,0)),2)+
		ROUND(SUM(coalesce(`employeer_igss`,0)),2)
    ,2)
    ,2)  AS `42`
    FROM
    (
    SELECT
    IF(`term_v`.valid_from IS NOT NULL, '0', '1') AS `active`,
    clientNetSuite,
    accounts.name,
    hires.nearsol_id,
    employees.client_id,
    `decreto`.`decreto_amount`,
    employees.job_type AS `job_type`,
    CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee name`,
    ROUND(IF(employees.job_type = 1, 0, employees.base_payment),2) AS `base_pay`,
    ROUND(IF(employees.job_type = 1,IF(employees.cost_type IS NULL, 0, ((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250)))), employees.productivity_payment),2) AS `productivity_pay`,
    ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.discounted_days,2)),2) AS `discounted_days`,
    ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.seventh,2)),2) AS `discounted_senths`,
    ROUND(IF(employees.job_type = 1, 0, payroll_values. discounted_hours),2) AS `hours`,
    ROUND(IF(employees.job_type = 1, 0, ROUND(payments.base - ROUND((payments.base_complete/2),2), 2)),2) AS `wage_deductions`,
    ROUND(IF(employees.job_type = 1, 
    IF(employees.cost_type IS NULL, 0,
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)
    ),
    ROUND(payments.productivity - ROUND(payments.productivity_complete/2,2), 2)),2) AS `incentive_deductions`,
    ROUND(IF(employees.job_type = 1, 0, payments.base),2) AS `base`,
    ROUND(IF(employees.job_type = 1, coalesce(IF(employees.job_type = 1,
    (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)), payments.productivity),0), payments.productivity),2) AS `productivity`,
    ROUND(payments.ot_hours,2) AS `ot_hours`,
    ROUND(payments.ot,2) AS `ot`,
    ROUND(payments.holidays_hours,2) AS `holidays_hours`,
    ROUND(payments.holidays,2) AS `holidays`,
    `bonuses`.`bonuses_amount`,
    `treasure_hunt`.`trasure_amount`,
    `adjustments_positive`.`adjustment`,    
    IF(employees.job_type IS NULL, `bus_service`.`bus_amount`,0) AS `bus_amount`,
    IF(employees.job_type IS NULL, `car_parking`.`car_amount`,0) AS `car_amount`,
    IF(employees.job_type IS NULL, `motorcycle_parking`.`motorcycle_amount`,0) AS `motorcycle_amount`,
    `igss`.`igss_amount`,
    `isr`.`isr_amount`,
    0 AS `headsets_amount`,
    
    ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
    coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2) AS `total_deductions`,
    
    ROUND((ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
    + coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
    coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, payments.base),0) + coalesce(IF(employees.job_type = 1,
    (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)), payments.productivity),0),2)) -
    ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + coalesce(`bus_service`.`bus_amount`,0) +
    coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2),2
    ) AS `total_payment`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`) AS `base_aguinaldo`,
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)), `severances`.`amount_productivity_aguinaldo`) AS `productivity_aguinaldo`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`) AS `base_bono14`,
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_bono14`)), `severances`.`amount_productivity_bono14`) AS `productivity_bono14`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`) AS `base_vacaciones`,
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)), `severances`.`amount_productivity_vacaciones`) AS `productivity_vacaciones`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`) AS `base_indemnizacion`,
    ROUND((IF(employees.job_type = 1, 0, COALESCE(payments.base,0))+
    ROUND(COALESCE(payments.ot,0),2)+
    ROUND(COALESCE(payments.holidays,0),2)) * 0.1267, 2) AS `employeer_igss`,
    198.24 AS `health`,
    0 AS `PARKING`,
    0 AS `BUS`,
    ROUND(ROUND((IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)), `severances`.`amount_productivity_aguinaldo`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_bono14`)), `severances`.`amount_productivity_bono14`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)), `severances`.`amount_productivity_vacaciones`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)),2) + ROUND((IF(employees.job_type = 1, 0, payments.base)+
    ROUND(payments.ot,2)+
    ROUND(payments.holidays,2)) * 0.1267, 2) + 198.24 ,2) AS `total_reserves_and_fees`,
    ROUND(ROUND(IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)), `severances`.`amount_productivity_aguinaldo`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_bono14`)), `severances`.`amount_productivity_bono14`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)), `severances`.`amount_productivity_vacaciones`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)+
    ROUND((IF(employees.job_type = 1, 0, payments.base)+
    ROUND(payments.ot,2)+
    ROUND(payments.holidays, 2)) * 0.1267, 2),2)+
    ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
    + coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
    coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, coalesce(IF(employees.job_type = 1,
    (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)), payments.productivity),0), payments.productivity),0) + coalesce(IF(employees.job_type = 1, 0, payments.base),0), 2) 
     + 198.24,2) AS `total_cost`
    FROM payments
    INNER JOIN employees ON employees.idemployees = payments.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN payroll_values ON payroll_values.id_payment = payments.idpayments
    INNER JOIN accounts ON accounts.idaccounts = payments.id_account_py
    LEFT JOIN (
        SELECT
        valid_from, id_employee FROM
        terminations
        INNER JOIN hr_processes ON terminations.id_process = hr_processes.idhr_processes
        INNER JOIN periods ON LAST_DAY(periods.start) >= terminations.valid_from
            AND DATE_ADD(DATE_ADD(LAST_DAY(periods.start),INTERVAL 1 DAY),INTERVAL -1 MONTH) <= terminations.valid_from
            AND periods.idperiods = $id_1
        WHERE id_type = 8
    ) AS `term_v` ON `term_v`.id_employee = payments.id_employee
     LEFT JOIN (
                SELECT
                coalesce(ROUND(SUM(credits.amount),2),0) AS `decreto_amount`,
                credits.id_payment
                FROM credits
                INNER JOIN payments ON payments.idpayments = credits.id_payment
                INNER JOIN employees ON employees.idemployees = payments.id_employee
                WHERE (credits.type = 'Bonificacion Decreto' AND employees.job_type IS NULL)
                GROUP BY id_payment
              ) AS `decreto` ON `decreto`.id_payment = payments.idpayments
    LEFT JOIN (
                SELECT 
                coalesce(ROUND(SUM(credits.amount),2),0) AS `bonuses_amount`,
                credits.id_payment
                FROM credits
                INNER JOIN payments ON payments.idpayments = credits.id_payment
                INNER JOIN employees ON employees.idemployees = payments.id_employee
                WHERE (
                        (credits.type != 'Salario Base'
                        AND credits.type != 'Bonificacion Productividad'
                        AND credits.type != 'Treasure Hunt'
                        AND credits.type != 'Bonificacion Decreto'
                        AND credits.type NOT LIKE '%RAF%'
                        AND credits.type NOT LIKE '%Nearsol TK%'
                        AND credits.type NOT LIKE '%Horas Extra%'
                        AND credits.type NOT LIKE '%Horas de Asueto%'
                        AND credits.type NOT LIKE '%Ajuste%')
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
                coalesce(ROUND(SUM(debits.amount),2),0) AS `bus_amount`,
                id_payment
                FROM debits
                WHERE debits.type LIKE '%bus%'
                GROUP BY id_payment
               ) AS `bus_service` ON `bus_service`.id_payment = payments.idpayments
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
                INNER JOIN payments ON payments.idpayments = debits.id_payment
                INNER JOIN employees ON employees.idemployees = payments.id_employee
                WHERE debits.type LIKE '%isr%'
                GROUP BY id_payment
               ) AS `isr` ON `isr`.id_payment = payments.idpayments AND employees.job_type IS NULL AND `term_v`.valid_from IS NULL
    INNER JOIN (
                SELECT
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.08333333333*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2)
                AS `amount_base_aguinaldo`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.0972*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2) AS `amount_base_indemnizacion`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)+ 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.08333333333*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2) AS `amount_base_bono14`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.04166666666*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2) AS `amount_base_vacaciones`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.`start`)+1)
                ))*0.08333333333*(IF(e.job_type IS NULL, ((250 + pay.productivity_complete)/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1)),
                ((pay.productivity_complete - (e.max_cost - pay.base_complete - 250))/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))
                )),2) AS `amount_productivity_aguinaldo`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.08333333333*(IF(e.job_type IS NULL, ((250 + pay.productivity_complete)/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1)),
                ((pay.productivity_complete - (e.max_cost - pay.base_complete - 250))/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))
                )),2) AS `amount_productivity_bono14`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.041666666*(IF(e.job_type IS NULL, ((250 + pay.productivity_complete)/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1)),
                ((pay.productivity_complete - (e.max_cost - pay.base_complete - 250))/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))
                )),2) AS `amount_productivity_vacaciones`,
                pay.idpayments
                FROM payments pay
                INNER JOIN employees e ON (e.idemployees = pay.id_employee)
                INNER JOIN periods p ON (p.idperiods = pay.id_period)
                INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
                LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                            INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                            WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
                WHERE pay.id_period = $id_1
                ) AS `severances` ON `severances`.idpayments = payments.idpayments
    WHERE (payments.id_period = $id_1) and clientNetSuite = $netsuitclient
    UNION
    SELECT    
    clientNetSuite,
    IF(`term_v`.valid_from IS NOT NULL, '0', '1') AS `active`,
    accounts.name,
    hires.nearsol_id,
    employees.client_id,
    `decreto`.`decreto_amount`,
    employees.job_type AS `job_type`,
    CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee name`,
    ROUND(IF(employees.job_type = 1, 0, employees.base_payment),2) AS `base_pay`,
    ROUND(IF(employees.job_type = 1,IF(employees.cost_type IS NULL, 0, ((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250)))), employees.productivity_payment),2) AS `productivity_pay`,
    ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.discounted_days,2)),2) AS `discounted_days`,
    ROUND(IF(employees.job_type = 1, 0, ROUND(0-payroll_values.seventh,2)),2) AS `discounted_senths`,
    ROUND(IF(employees.job_type = 1, 0, payroll_values. discounted_hours),2) AS `hours`,
    ROUND(IF(employees.job_type = 1, 0, ROUND(payments.base - ROUND((payments.base_complete/2),2), 2)),2) AS `wage_deductions`,
    ROUND(IF(employees.job_type = 1, 
    IF(employees.cost_type IS NULL, 0,
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)
    ),
    ROUND(payments.productivity - ROUND(payments.productivity_complete/2,2), 2)),2) AS `incentive_deductions`,
    ROUND(IF(employees.job_type = 1, 0, payments.base),2) AS `base`,
    ROUND(IF(employees.job_type = 1, coalesce(IF(employees.job_type = 1,
    (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)), payments.productivity),0), payments.productivity),2) AS `productivity`,
    ROUND(payments.ot_hours,2) AS `ot_hours`,
    ROUND(payments.ot,2) AS `ot`,
    ROUND(payments.holidays_hours,2) AS `holidays_hours`,
    ROUND(payments.holidays,2) AS `holidays`,
    `bonuses`.`bonuses_amount`,
    `treasure_hunt`.`trasure_amount`,
    `adjustments_positive`.`adjustment`,    
    IF(employees.job_type IS NULL, `bus_service`.`bus_amount`,0) AS `bus_amount`,
    IF(employees.job_type IS NULL, `car_parking`.`car_amount`,0) AS `car_amount`,
    IF(employees.job_type IS NULL, `motorcycle_parking`.`motorcycle_amount`,0) AS `motorcycle_amount`,
    `igss`.`igss_amount`,
    `isr`.`isr_amount`,
    0 AS `headsets_amount`,
    ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
    coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2) AS `total_deductions`,
    ROUND((ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
    + coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
    coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, 0, payments.base),0) + coalesce(IF(employees.job_type = 1,
    (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)), payments.productivity),0),2)) -
    ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + + coalesce(`bus_service`.`bus_amount`,0) +
    coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(`headset`.`headsets_amount`,0), 2), 2
    ) AS `total_payment`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`) AS `base_aguinaldo`,
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)), `severances`.`amount_productivity_aguinaldo`) AS `productivity_aguinaldo`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`) AS `base_bono14`,
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_bono14`)), `severances`.`amount_productivity_bono14`) AS `productivity_bono14`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`) AS `base_vacaciones`,
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)), `severances`.`amount_productivity_vacaciones`) AS `productivity_vacaciones`,
    IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`) AS `base_indemnizacion`,
    ROUND((IF(employees.job_type = 1, 0, COALESCE(payments.base,0))+
    ROUND(COALESCE(payments.ot,0),2)+
    ROUND(COALESCE(payments.holidays,0),2)) * 0.1267, 2) AS `employeer_igss`,
    198.24 AS `health`,
    0 AS `PARKING`,
    0 AS `BUS`,
    ROUND(ROUND((IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)), `severances`.`amount_productivity_aguinaldo`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_bono14`)), `severances`.`amount_productivity_bono14`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)), `severances`.`amount_productivity_vacaciones`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)),2) + ROUND((IF(employees.job_type = 1, 0, payments.base)+
    ROUND(payments.ot,2)+
    ROUND(payments.holidays,2)) * 0.1267, 2) + 198.24 ,2) AS `total_reserves_and_fees`,
    ROUND(ROUND(IF(employees.job_type = 1, 0, `severances`.`amount_base_aguinaldo`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_bono14`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_vacaciones`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)), `severances`.`amount_productivity_aguinaldo`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_bono14`)), `severances`.`amount_productivity_bono14`)+
    IF(employees.job_type = 1, (IF(employees.cost_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)), `severances`.`amount_productivity_vacaciones`)+
    IF(employees.job_type = 1, 0, `severances`.`amount_base_indemnizacion`)+
    ROUND((IF(employees.job_type = 1, 0, payments.base)+
    ROUND(payments.ot,2)+
    ROUND(payments.holidays,2)) * 0.1267, 2),2)+
    ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
    + coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(payments.holidays,0) + 
    coalesce(payments.ot,0) + coalesce(IF(employees.job_type = 1, coalesce(IF(employees.job_type = 1,
    (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
    ROUND(((payments.productivity_complete/2) - payments.productivity)-(
    ((
    ((payments.productivity_complete/2) - payments.productivity)/
    ((payments.productivity_complete/2)/120)
    )/120
    )*((employees.max_cost - payments.base_complete - 250)/2)),2)), payments.productivity),0), payments.productivity),0) + coalesce(IF(employees.job_type = 1, 0, payments.base),0), 2) 
     + 198.24,2) AS `total_cost`
    FROM payments
    INNER JOIN employees ON employees.idemployees = payments.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN payroll_values ON payroll_values.id_payment = payments.idpayments
    INNER JOIN accounts ON accounts.idaccounts = payments.id_account_py
    LEFT JOIN (
        SELECT
        valid_from, id_employee FROM
        terminations
        INNER JOIN hr_processes ON terminations.id_process = hr_processes.idhr_processes
        INNER JOIN periods ON LAST_DAY(periods.start) >= terminations.valid_from
            AND DATE_ADD(DATE_ADD(LAST_DAY(periods.start),INTERVAL 1 DAY),INTERVAL -1 MONTH) <= terminations.valid_from
            AND periods.idperiods = $id_1
        WHERE id_type = 8
    ) AS `term_v` ON `term_v`.id_employee = payments.id_employee
    LEFT JOIN (
                SELECT
                coalesce(ROUND(SUM(credits.amount),2),0) AS `decreto_amount`,
                credits.id_payment
                FROM credits
                INNER JOIN payments ON payments.idpayments = credits.id_payment
                INNER JOIN employees ON employees.idemployees = payments.id_employee
                WHERE (credits.type = 'Bonificacion Decreto' AND employees.job_type IS NULL)
                GROUP BY id_payment
              ) AS `decreto` ON `decreto`.id_payment = payments.idpayments
    LEFT JOIN (
    SELECT 
                coalesce(ROUND(SUM(credits.amount),2),0) AS `bonuses_amount`,
                credits.id_payment
                FROM credits
                INNER JOIN payments ON payments.idpayments = credits.id_payment
                INNER JOIN employees ON employees.idemployees = payments.id_employee
                WHERE (
                        (credits.type != 'Salario Base'
                        AND credits.type != 'Bonificacion Productividad'
                        AND credits.type != 'Treasure Hunt'
                        AND credits.type != 'Bonificacion Decreto'
                        AND credits.type NOT LIKE '%RAF%'
                        AND credits.type NOT LIKE '%Nearsol TK%'
                        AND credits.type NOT LIKE '%Horas Extra%'
                        AND credits.type NOT LIKE '%Horas de Asueto%'
                        AND credits.type NOT LIKE '%Ajuste%')
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
                coalesce(ROUND(SUM(debits.amount),2),0) AS `bus_amount`,
                id_payment
                FROM debits
                WHERE debits.type LIKE '%bus%'
                GROUP BY id_payment
               ) AS `bus_service` ON `bus_service`.id_payment = payments.idpayments
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
                INNER JOIN payments ON payments.idpayments = debits.id_payment
                INNER JOIN employees ON employees.idemployees = payments.id_employee
                WHERE debits.type LIKE '%isr%' AND employees.job_type IS NULL
                GROUP BY id_payment
               ) AS `isr` ON `isr`.id_payment = payments.idpayments AND `term_v`.valid_from IS NULL
    INNER JOIN (
                SELECT
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.08333333333*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2)
                AS `amount_base_aguinaldo`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.0972*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2) AS `amount_base_indemnizacion`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)+ 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.08333333333*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2) AS `amount_base_bono14`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.04166666666*((pay.base_complete/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))),2) AS `amount_base_vacaciones`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.`start`)+1)
                ))*0.08333333333*(IF(e.job_type IS NULL, ((250 + pay.productivity_complete)/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1)),
                ((pay.productivity_complete - (e.max_cost - pay.base_complete - 250))/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))
                )),2) AS `amount_productivity_aguinaldo`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.08333333333*(IF(e.job_type IS NULL, ((250 + pay.productivity_complete)/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1)),
                ((pay.productivity_complete - (e.max_cost - pay.base_complete - 250))/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))
                )),2) AS `amount_productivity_bono14`,
                ROUND((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date) + 1),
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                ))*0.041666666*(IF(e.job_type IS NULL, ((250 + pay.productivity_complete)/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1)),
                ((pay.productivity_complete - (e.max_cost - pay.base_complete - 250))/(DATEDIFF(LAST_DAY(p.start), date_add(p.start,interval -DAY(p.start)+1 DAY)) + 1))
                )),2) AS `amount_productivity_vacaciones`,
                pay.idpayments
                FROM payments pay
                INNER JOIN employees e ON (e.idemployees = pay.id_employee)
                INNER JOIN periods p ON (p.idperiods = pay.id_period)
                INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
                LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                            INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                            WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
                WHERE pay.id_period = $id_2
                ) AS `severances` ON `severances`.idpayments = payments.idpayments
    WHERE (payments.id_period = $id_2) and clientNetSuite = $netsuitclient
    ) AS `tmp`
    GROUP BY
    client_id,
    `employee name`,
    name,
    nearsol_id";

}else{
$netsuitclient = $netsuitclient - 6;
$sql = "SELECT
client_id,
`employee name`,
name,
nearsol_id,
ROUND(SUM(coalesce(`base_pay`/2,0)),2),
ROUND(SUM(coalesce(`productivity_pay`/2,0)),2),
ROUND(SUM(coalesce(`discounted_days`,0)),2),
ROUND(SUM(coalesce(`discounted_senths`,0)),2),
ROUND(SUM(coalesce(`hours`,0)),2),
ROUND(SUM(coalesce(`wage_deductions`,0)),2),
ROUND(SUM(coalesce(`incentive_deductions`,0)),2),
ROUND(SUM(coalesce(`base`,0)),2),
ROUND(SUM(coalesce(`productivity`,0)),2),
ROUND(SUM(coalesce(`ot_hours`,0)),2),
ROUND(SUM(coalesce(`ot`,0)),2),
ROUND(SUM(coalesce(`holidays_hours`,0)),2),
ROUND(SUM(coalesce(`holidays`,0)),2),
ROUND(SUM(coalesce(`bonuses_amount`,0)),2),
ROUND(SUM(coalesce(`trasure_amount`,0)),2),
ROUND(SUM(coalesce(`adjustment`,0)),2), 
ROUND(SUM(coalesce(`total_income`,0)),2),
ROUND(SUM(coalesce(`bus_amount`,0)),2),
ROUND(SUM(coalesce(`car_amount`,0)),2),
ROUND(SUM(coalesce(`motorcycle_amount`,0)),2),
ROUND(SUM(coalesce(`igss_amount`,0)),2),
ROUND(SUM(coalesce(`isr_amount`,0)),2),
0,
ROUND(SUM(coalesce(`total_deductions`,0)),2),
ROUND(SUM(coalesce(`total_payment`,0)),2),
ROUND(SUM(coalesce(`base_aguinaldo`,0)),2),
ROUND(SUM(coalesce(`productivity_aguinaldo`,0)),2),
ROUND(SUM(coalesce(`base_bono14`,0)),2),
ROUND(SUM(coalesce(`productivity_bono14`,0)),2),
ROUND(SUM(coalesce(`base_vacaciones`,0)),2),
ROUND(SUM(coalesce(`productivity_vacaciones`,0)),2),
ROUND(SUM(coalesce(`base_indemnizacion`,0)),2),
ROUND(SUM(coalesce(`employeer_igss`,0)),2),
ROUND(SUM(coalesce(`health`,0)),2),
SUM(coalesce(`PARKING`,0)),
SUM(coalesce(`BUS`,0)),
ROUND(SUM(coalesce(`total_reserves_and_fees`,0)),2),
ROUND(SUM(coalesce(`total_cost`,0)),2)
FROM
(
SELECT
employees.job_type,
payments.idpayments,
clientNetSuite,
accounts.name,
hires.nearsol_id,
employees.client_id,
CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee name`,
ROUND(IF(employees.job_type IS NULL, 0, employees.base_payment/2),2) AS `base_pay`,
ROUND(IF(employees.job_type IS NULL,IF(employees.cost_type IS NULL, 0, ((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2)), employees.productivity_payment/2),2) AS `productivity_pay`,
ROUND(IF(employees.job_type IS NULL, 0, ROUND(0-payroll_values.discounted_days,2)),2) AS `discounted_days`,
ROUND(IF(employees.job_type IS NULL, 0, ROUND(0-payroll_values.seventh,2)),2) AS `discounted_senths`,
ROUND(IF(employees.job_type IS NULL, 0, payroll_values. discounted_hours),2) AS `hours`,
ROUND(IF(employees.job_type IS NULL, 0, ROUND(payments.base - ROUND((payments.base_complete/2),2), 2)),2) AS `wage_deductions`,
ROUND(IF(employees.job_type IS NULL, 
IF(employees.cost_type IS NULL, 0,
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2)
),
ROUND(payments.productivity - ROUND(payments.productivity_complete/2,2), 2)),2) AS `incentive_deductions`,
ROUND(IF(employees.job_type IS NULL, 0, payments.base),2) AS `base`,
ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2) AS `productivity`,
0 AS `ot_hours`,
0 AS `ot`,
0 AS `holidays_hours`,
0 AS `holidays`,
`bonuses`.`bonuses_amount`,
`treasure_hunt`.`trasure_amount`,
`adjustments_positive`.`adjustment`,

ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(IF(employees.job_type IS NULL,
payments.productivity, ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2)
),0) + coalesce(IF(employees.job_type IS NULL, 0, payments.base),0),2) AS `total_income`,

`bus_service`.`bus_amount` AS `bus_amount`,
`car_parking`.`car_amount` AS `car_amount`,
`motorcycle_parking`.`motorcycle_amount` AS `motorcycle_amount`,
(`igss`.`igss_amount`),
`isr`.`isr_amount`,
0,
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(0,0), 2) AS `total_deductions`,

ROUND((ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(IF(employees.job_type IS NULL, 0, payments.base),0) + coalesce(IF(employees.job_type IS NULL,0, 
IF(employees.cost_type IS NULL, payments.productivity,
ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2)
)
),0),2)) -
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + coalesce(`bus_service`.`bus_amount`,0) +
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(0,0), 2),2
) AS `total_payment`,

IF(employees.job_type IS NULL, 0, `severances`.`amount_base_aguinaldo`) AS `base_aguinaldo`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`) AS `productivity_aguinaldo`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_bono14`) AS `base_bono14`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_bono14`) AS `productivity_bono14`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_vacaciones`) AS `base_vacaciones`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`) AS `productivity_vacaciones`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_indemnizacion`) AS `base_indemnizacion`,
ROUND((IF(employees.job_type IS NULL, 0, payments.base)) * 0.1267, 2) AS `employeer_igss`,
198.24 AS `health`,
0 AS `PARKING`,
0 AS `BUS`,
ROUND(ROUND((IF(employees.job_type IS NULL, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_indemnizacion`)),2) + ROUND((IF(employees.job_type IS NULL, 0, payments.base)) * 0.1267, 2) + 198.24 ,2) AS `total_reserves_and_fees`,
ROUND(ROUND(IF(employees.job_type IS NULL, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_indemnizacion`)+
ROUND((IF(employees.job_type IS NULL, 0, payments.base)) * 0.1267, 2),2)+
ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(IF(employees.job_type IS NULL,
payments.productivity, ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2)
),0) + coalesce(IF(employees.job_type IS NULL, 0, payments.base),0),2)
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
            INNER JOIN payments ON payments.idpayments = credits.id_payment
            INNER JOIN employees ON employees.idemployees = payments.id_employee
            WHERE (
					(credits.type != 'Salario Base'
                    AND credits.type != 'Bonificacion Productividad'
                    AND credits.type != 'Treasure Hunt'
                    AND credits.type NOT LIKE '%Horas Extra%'
                    AND credits.type NOT LIKE '%Horas de Asueto%'
                    AND credits.type NOT LIKE '%Bonos Diversos Cliente TK%'
                    AND credits.type NOT LIKE '%Ajuste%'
                    AND credits.type NOT LIKE '%Bonificacion Decreto%')
                    OR (credits.type LIKE '%Bonificacion Decreto%' AND employees.job_type IS NOT NULL)
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
            coalesce(ROUND(SUM(debits.amount),2),0) AS `bus_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%bus%'
            GROUP BY id_payment
		   ) AS `bus_service` ON `bus_service`.id_payment = payments.idpayments
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
            IF(employees.job_type IS NULL, 0, ROUND(debits.amount - ROUND((payments.ot + payments.holidays)*0.0483,2),2)) AS `igss_amount`,
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
            coalesce(ROUND(SUM(IF(employees.cost_type = 1, debits.amount, 0)),2),0) AS `isr_amount`,
            id_payment
            FROM debits
            INNER JOIN payments ON payments.idpayments = debits.id_payment
            INNER JOIN employees ON employees.idemployees = payments.id_employee
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
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(IF(e.cost_type IS NULL, ((pay.productivity_complete/2) + 125)/2, (((e.max_cost - pay.base_complete))/2)
            )),2) AS `amount_productivity_aguinaldo`,
			ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(IF(e.cost_type IS NULL, ((pay.productivity_complete/2) + 125)/2, (((e.max_cost - pay.base_complete))/2)
            )),2) AS `amount_productivity_bono14`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(IF(e.cost_type IS NULL, ((pay.productivity_complete/2) + 125)/2, (((e.max_cost - pay.base_complete))/2)
            )),2) AS `amount_productivity_vacaciones`,
            pay.idpayments
			FROM payments pay
			INNER JOIN employees e ON (e.idemployees = pay.id_employee)
			INNER JOIN periods p ON (p.idperiods = pay.id_period)
			INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
			LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
						INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
						WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
			WHERE pay.id_period = $id_1
			) AS `severances` ON `severances`.idpayments = payments.idpayments
WHERE (payments.id_period = $id_1) and clientNetSuite = $netsuitclient
UNION
SELECT
employees.job_type,
payments.idpayments,
clientNetSuite,
accounts.name,
hires.nearsol_id,
employees.client_id,
CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee name`,
ROUND(IF(employees.job_type IS NULL, 0, employees.base_payment/2),2) AS `base_pay`,
ROUND(IF(employees.job_type IS NULL,IF(employees.cost_type IS NULL, 0, ((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2)), employees.productivity_payment/2),2) AS `productivity_pay`,
ROUND(IF(employees.job_type IS NULL, 0, ROUND(0-payroll_values.discounted_days,2)),2) AS `discounted_days`,
ROUND(IF(employees.job_type IS NULL, 0, ROUND(0-payroll_values.seventh,2)),2) AS `discounted_senths`,
ROUND(IF(employees.job_type IS NULL, 0, payroll_values. discounted_hours),2) AS `hours`,
ROUND(IF(employees.job_type IS NULL, 0, ROUND(payments.base - ROUND((payments.base_complete/2),2), 2)),2) AS `wage_deductions`,
ROUND(IF(employees.job_type IS NULL, 
IF(employees.cost_type IS NULL, 0,
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2)
),
ROUND(payments.productivity - ROUND(payments.productivity_complete/2,2), 2)),2) AS `incentive_deductions`,
ROUND(IF(employees.job_type IS NULL, 0, payments.base),2) AS `base`,
ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2) AS `productivity`,
0 AS `ot_hours`,
0 AS `ot`,
0 AS `holidays_hours`,
0 AS `holidays`,
`bonuses`.`bonuses_amount`,
`treasure_hunt`.`trasure_amount`,
`adjustments_positive`.`adjustment`,

ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(IF(employees.job_type IS NULL,
payments.productivity, ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2)
),0) + coalesce(IF(employees.job_type IS NULL, 0, payments.base),0),2) AS `total_income`,

`bus_service`.`bus_amount` AS `bus_amount`,
`car_parking`.`car_amount` AS `car_amount`,
`motorcycle_parking`.`motorcycle_amount` AS `motorcycle_amount`,
(`igss`.`igss_amount`),
`isr`.`isr_amount`,
0,
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + 
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(0,0), 2) AS `total_deductions`,

ROUND((ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(IF(employees.job_type IS NULL, 0, payments.base),0) + coalesce(IF(employees.job_type IS NULL,0, 
IF(employees.cost_type IS NULL, payments.productivity,
ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2)
)
),0),2)) -
ROUND(coalesce(`car_parking`.`car_amount`,0) + coalesce(`motorcycle_parking`.`motorcycle_amount`,0) + coalesce(`bus_service`.`bus_amount`,0) +
coalesce(`igss`.`igss_amount`,0) + coalesce(`isr`.`isr_amount`,0) + coalesce(0,0), 2),2
) AS `total_payment`,

IF(employees.job_type IS NULL, 0, `severances`.`amount_base_aguinaldo`) AS `base_aguinaldo`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`) AS `productivity_aguinaldo`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_bono14`) AS `base_bono14`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_bono14`) AS `productivity_bono14`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_vacaciones`) AS `base_vacaciones`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`) AS `productivity_vacaciones`,
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_indemnizacion`) AS `base_indemnizacion`,
ROUND((IF(employees.job_type IS NULL, 0, payments.base)) * 0.1267, 2) AS `employeer_igss`,
198.24 AS `health`,
0 AS `PARKING`,
0 AS `BUS`,
ROUND(ROUND((IF(employees.job_type IS NULL, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_indemnizacion`)),2) + ROUND((IF(employees.job_type IS NULL, 0, payments.base)) * 0.1267, 2) + 198.24 ,2) AS `total_reserves_and_fees`,
ROUND(ROUND(IF(employees.job_type IS NULL, 0, `severances`.`amount_base_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_aguinaldo`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_bono14`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_productivity_vacaciones`)+
IF(employees.job_type IS NULL, 0, `severances`.`amount_base_indemnizacion`)+
ROUND((IF(employees.job_type IS NULL, 0, payments.base)) * 0.1267, 2),2)+
ROUND(coalesce(`adjustments_positive`.`adjustment`,0) + coalesce(`treasure_hunt`.`trasure_amount`,0) 
+ coalesce(`bonuses`.`bonuses_amount`,0) + coalesce(IF(employees.job_type IS NULL,
payments.productivity, ROUND(IF(employees.job_type IS NULL, payments.productivity, coalesce(IF(employees.cost_type IS NULL, payments.productivity,
payments.productivity - (ROUND(((payments.productivity_complete - (employees.max_cost - payments.base_complete - 250))/2),2) -
ROUND(((payments.productivity_complete/2) - payments.productivity)-(
((
((payments.productivity_complete/2) - payments.productivity)/
((payments.productivity_complete/2)/120)
)/120
)*((employees.max_cost - payments.base_complete - 250)/2)),2))),0)),2)
),0) + coalesce(IF(employees.job_type IS NULL, 0, payments.base),0),2)
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
            INNER JOIN payments ON payments.idpayments = credits.id_payment
            INNER JOIN employees ON employees.idemployees = payments.id_employee
            WHERE (
					(credits.type != 'Salario Base'
                    AND credits.type != 'Bonificacion Productividad'
                    AND credits.type != 'Treasure Hunt'
                    AND credits.type NOT LIKE '%Bonos Diversos Cliente TK%'
                    AND credits.type NOT LIKE '%Horas Extra%'
                    AND credits.type NOT LIKE '%Horas de Asueto%'
                    AND credits.type NOT LIKE '%Ajuste%'
                    AND credits.type NOT LIKE '%Bonificacion Decreto%')
                    OR (credits.type LIKE '%Bonificacion Decreto%' AND employees.job_type IS NOT NULL)
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
            coalesce(ROUND(SUM(debits.amount),2),0) AS `bus_amount`,
            id_payment
            FROM debits
            WHERE debits.type LIKE '%bus%'
            GROUP BY id_payment
		   ) AS `bus_service` ON `bus_service`.id_payment = payments.idpayments
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
            IF(employees.job_type IS NULL, 0, ROUND(debits.amount - ROUND((payments.ot + payments.holidays),2),2)) AS `igss_amount`,
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
            coalesce(ROUND(SUM(IF(employees.cost_type = 1, debits.amount, 0)),2),0) AS `isr_amount`,
            id_payment
            FROM debits
            INNER JOIN payments ON payments.idpayments = debits.id_payment
            INNER JOIN employees ON employees.idemployees = payments.id_employee
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
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(IF(e.cost_type IS NULL, ((pay.productivity_complete/2) + 125)/2, (((e.max_cost - pay.base_complete))/2)
            )),2) AS `amount_productivity_aguinaldo`,
			ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(IF(e.cost_type IS NULL, ((pay.productivity_complete/2) + 125)/2, (((e.max_cost - pay.base_complete))/2)
            )),2) AS `amount_productivity_bono14`,
            ROUND((IF(e.hiring_date>p.start,
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
			IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
			)) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(IF(e.cost_type IS NULL, ((pay.productivity_complete/2) + 125)/2, (((e.max_cost - pay.base_complete))/2)
            )),2) AS `amount_productivity_vacaciones`,
            pay.idpayments
			FROM payments pay
			INNER JOIN employees e ON (e.idemployees = pay.id_employee)
			INNER JOIN periods p ON (p.idperiods = pay.id_period)
			INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
			LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
						INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
						WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
			WHERE pay.id_period = $id_2
			) AS `severances` ON `severances`.idpayments = payments.idpayments
WHERE (payments.id_period = $id_2) AND clientNetSuite = $netsuitclient
) AS `tmp` WHERE job_type = 1
GROUP BY
clientNetSuite,
name,
nearsol_id,
client_id,
`employee name`;";
}
echo($sql);
$output = fopen("php://output", "w");
fputcsv($output, array('Avaya','Name','Account', 'Nearsol ID','Minimum Wage','Incentive','Days discounted','7th deduction','Discounted hours','Minimum Wage Deductions','Incentive Deductions','Minimum Wage with deductions','Incentive with deductions','Overtime (hours)','Overtime (Q)','Holiday (hours)','Holiday (Q)','Bonuses','Treasure Hunt','Adjustments','Total income','Bus','Parking (Car)','Parking Motorcycle / bicycle','IGSS','ISR','Equipment','Total Deductions','Total Payment','BONUS 13','BONUS 13 BONIF','BONUS 14 ','BONUS 14 BONIF','VACATION RESERVES','VACATION RESERVES BONIF','SEVERANCE RESERVES','EMPLOYER IGSS','HEALTH INSURANCE','PARKING','BUS','TOTAL RESERVES AND FEES','TOTAL COST',));
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            $rowExport[0] = $row[1];
            $rowExport[1] = $row[2];
            $rowExport[2] = $row[3];
            $rowExport[3] = $row[4];
            $rowExport[4] = $row[5];
            $rowExport[5] = $row[6];
            $rowExport[6] = $row[7];
            $rowExport[7] = $row[8];
            $rowExport[8] = $row[9];
            $rowExport[9] = $row[10];
            $rowExport[10] = $row[11];
            $rowExport[11] = $row[12];
            $rowExport[12] = $row[13];
            $rowExport[13] = $row[14];
            $rowExport[14] = $row[15];
            $rowExport[15] = $row[16];
            $rowExport[16] = $row[17];
            $rowExport[17] = $row[18];
            $rowExport[18] = $row[19];
            $rowExport[19] = $row[20];
            $rowExport[20] = $row[21];
            $rowExport[21] = $row[22];
            $rowExport[22] = $row[23];
            $rowExport[23] = $row[24];
            $rowExport[24] = $row[25];
            $rowExport[25] = $row[26];
            $rowExport[26] = $row[27];
            $rowExport[27] = $row[28];
            $rowExport[28] = $row[29];
            $rowExport[29] = $row[30];
            $rowExport[30] = $row[31];
            $rowExport[31] = $row[32];
            $rowExport[32] = $row[33];
            $rowExport[33] = $row[34];
            $rowExport[34] = $row[35];
            $rowExport[35] = $row[36];
            $rowExport[36] = $row[37];
            $rowExport[37] = $row[38];
            $rowExport[38] = $row[39];
            $rowExport[39] = $row[40];
            $rowExport[40] = $row[41];
            $rowExport[41] = $row[42];
            fputcsv($output, $rowExport, ",");
            $i++;
        };
    }else{
        echo($sql);
    }
fclose($output);
?>