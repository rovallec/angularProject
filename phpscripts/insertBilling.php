<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$month = ($request->month);
$account = ($request->account);
$avaya = ($request->avaya);
$name = ($request->name);
$nearsol_id = ($request->nearsol_id);
$minimum_wage = ($request->minimum_wage);
$incentive = ($request->incentive);
$days_discounted = ($request->days_discounted);
$deduction_7th = ($request->deduction_7th);
$discounted_hours = ($request->discounted_hours);
$minimum_wage_deductions = ($request->minimum_wage_deductions);
$incentive_deductions = ($request->incentive_deductions);
$minimum_wage_with_deductions = ($request->minimum_wage_with_deductions);
$incentive_with_deductions = ($request->incentive_with_deductions);
$overtime_hours = ($request->overtime_hours);
$overtime = ($request->overtime);
$holiday_hours = ($request->holiday_hours);
$holiday = ($request->holiday);
$bonuses = ($request->bonuses);
$treasure_hunt = ($request->treasure_hunt);
$adjustments = ($request->adjustments);
$total_income = ($request->total_income);
$bus = ($request->bus);
$parking_car = ($request->parking_car);
$parking_motorcycle_bicycle = ($request->parking_motorcycle_bicycle);
$igss = ($request->igss);
$isr = ($request->isr);
$equipment = ($request->equipment);
$total_deductions = ($request->total_deductios);
$total_payment = ($request->total_payment);
$bonus_13 = ($request->bonus_13);
$bonus_13_bonif = ($request->bonus_13_bonif);
$bonus_14 = ($request->bonus_14);
$bonus_14_bonif = ($request->bonus_14_bonif);
$vacation_reserves = ($request->vacation_reserves);
$vacation_reserves_bonif = ($request->vacation_reserves_bonif);
$severance_reserves = ($request->severance_reserves);
$employer_igss = ($request->employer_igss);
$health_insurance = ($request->health_insurance);
$parking = ($request->parking);
$bus_client = ($request->bus_client);
$total_reserves_and_fees = ($request->total_reserves_and_fees);
$total_cost = ($request->total_cost);
$today = date("Y/m/d");

$sql1 = "INSERT INTO billing (idbillings, id_account, `date`) 
        VALUES (null, $account, $today);";

if(mysqli_query($con, $sql1)){
    $id_billing = mysqli_insert_id($con);
    
    $sql2 = "INSERT INTO billing_details (id_billing, avaya,name,account,nearsol_id,minimum_wage,incentive,days_discounted,
        deduction_7th,discounted_hours,minimum_wage_deductions,incentive_deductions,
        minimum_wage_with_deductions,incentive_with_deductions,overtime_hours,overtime,
        holiday_hours,holiday,bonuses,treasure_hunt,adjustments,total_income,bus,
        parking_car,parking_motorcycle_bicycle,igss,isr,equipment,total_deductions,
        total_payment,bonus_13,bonus_13_bonif,bonus_14,bonus_14_bonif,vacation_reserves,
        vacation_reserves_bonif,severance_reserves,employer_igss,health_insurance,
        parking,bus_client,total_reserves_and_fees,total_cost)  
        SELECT
            $id_billing,
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
                coalesce(ROUND(SUM(coalesce(`adjustment`,0)),2),0),0),2)  AS `21`,    
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
            ,2) -   ROUND(
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
            payments.idpayments,
            employees.active,
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
            ROUND((IF(employees.job_type = 1, 0, payments.base)+
            ROUND(payments.ot,2)+
            ROUND(payments.holidays,2)) * 0.1267, 2) AS `employeer_igss`,
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
                        WHERE debits.type = 'Descuento IGSS' AND (id_period = @id_1)
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
                        WHERE debits.type LIKE '%isr%' AND employees.job_type IS NULL AND employees.active = 1
                        GROUP BY id_payment
                    ) AS `isr` ON `isr`.id_payment = payments.idpayments
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
                        WHERE pay.id_period = @id_1
                        ) AS `severances` ON `severances`.idpayments = payments.idpayments
            WHERE (payments.id_period = @id_1) and clientNetSuite = @netsuitclient
            UNION
            SELECT
            payments.idpayments,
            clientNetSuite,
            employees.active,
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
            ROUND((IF(employees.job_type = 1, 0, payments.base)+
            ROUND(payments.ot,2)+
            ROUND(payments.holidays,2)) * 0.1267, 2) AS `employeer_igss`,
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
                        WHERE debits.type = 'Descuento IGSS' AND (id_period = @id_2)
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
                        WHERE debits.type LIKE '%isr%' AND employees.job_type IS NULL AND employees.active = 1
                        GROUP BY id_payment
                    ) AS `isr` ON `isr`.id_payment = payments.idpayments
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
                        WHERE pay.id_period = @id_2
                        ) AS `severances` ON `severances`.idpayments = payments.idpayments
            WHERE (payments.id_period = @id_2) and clientNetSuite = @netsuitclient
            ) AS `tmp`
            GROUP BY clientNetSuite,
            name,
            nearsol_id,
            client_id,
            `employee name`;";
    if(mysqli_query($con, $sql2)){
        http_response_code(200);
    }else{
        http_response_code(404);
    }
}

?>