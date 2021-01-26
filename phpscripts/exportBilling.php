<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "activeAnalysis.csv" . '"');
require 'database.php';



$i = 0;
$start = $_GET['start'];
$end = $_GET['end'];
$account = $_GET['account'];
$rowExport = [];
echo "\xEF\xBB\xBF";
$sql2 = "SET @rownum = 0;";

if(mysqli_query($con,$sql2)){
    $sql = "SELECT
    @rownum := @rownum + 1 AS 'No.',
    `nearsol_id`,
    `client_id`,CONCAT(`first_name`, ' ',
    `second_name`, ' ',`first_lastname`, ' ',
    `second_lastname`) AS `employee_name`,
    SUM(`base`) AS `minimun_wage`,
    SUM(`incentive`) AS `incentive`,
    SUM(`discounted_days`) AS `days_discounted`,
    SUM(`sevenths_discounted`) AS `7th_deduction`,
    SUM(`discounted_hours`) AS `discounted_hours`,
    SUM(`wage_deductions`) AS `minimum_wage_deductions`,
    SUM(`incentive_deductions`) AS `incentive_deductions`,
    SUM(`base_with_deductions`) AS `minimum_wabe_with_deductions`,
    SUM(`productivity_with_deductions`) AS `incentive_with_deductions`,
    SUM(`ot_hours`) AS `overtime_hours`,
    SUM(`ot`) AS `overtime_amount`,
    SUM(`holidays_hours`) AS `holiday_hours`,
    SUM(`holidays`) AS `holiday_amount`,
    SUM(`bonuses`) AS `bonuses`,
    SUM(`tresure_hunt_bonus`) AS `treasure_hunt`,
    SUM(`adj`) AS `adjustments`,
    SUM(`total_income`) AS `total_income`,
    SUM(`bus_discount`) AS `bus`,
    SUM(`parking_car_discount`) AS `parking_car`,
    SUM(`parking_motorcycle_discount`) AS `parking_motorcycle_discount`,
    SUM(`igss_discount`) AS `igss_employee`,
    SUM(`isr_discount`) AS `isr_discount`,
    SUM(`equipment_discount`) AS `equipment_discount`,
    SUM(`total_deductions`) AS `total_deductions`,
    SUM(`total_payment`) AS `total_payment`,
    (SUM(`base`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) AS `bonus13_base`,
    (SUM(`incentive`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) AS `bonus13_productivity`,
    (SUM(`base`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) AS `bonus14_base`,
    (SUM(`incentive`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) AS `bonus14_productivity`,
    (SUM(`base`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (0.0417) AS `vacations_base`,
    (SUM(`incentive`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (0.0417) AS `vacations_prdouctivity`,
    (SUM(`base`)/Day(LAST_DAY('$end'))) * Day(LAST_DAY('$end')) * 0.0972	AS `severance_base`,
    (SUM(`incentive`)/Day(LAST_DAY('$end'))) * Day(LAST_DAY('$end')) * 0.0972	AS `severance_productivity`,
    ((coalesce(SUM(`base_with_deductions`),0) + coalesce(SUM(`ot`),0) + coalesce(SUM(`holidays`),0)) * 0.1267) AS `igss_employeer`,
    '198.24' AS `health_insurance`,
    IF(SUM(`parking_car_discount`)=175,300,IF(SUM(`parking_car_discount`)=350,300,SUM(`car_count`)*21.43)) AS `client_car_parking`,
    IF(SUM(`parking_motorcycle_discount`)=50,300,IF(SUM(`parking_motorcycle_discount`)=150,300,SUM(`motorcycle_count`)*21.43)) AS `client_motorcycle_parking`,

    (SUM(`base`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) +
    (SUM(`incentive`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) +
    (SUM(`base`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) +
    (SUM(`incentive`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (1/12) +
    (SUM(`base`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (0.0417) +
    (SUM(`incentive`)/DAY(LAST_DAY('$end'))) * DAY(LAST_DAY('$end')) * (0.0417) +
    (SUM(`base`)/Day(LAST_DAY('$end'))) * Day(LAST_DAY('$end')) * 0.0972 +
    (SUM(`incentive`)/Day(LAST_DAY('$end'))) * Day(LAST_DAY('$end')) * 0.0972 +
    ((coalesce(SUM(`base_with_deductions`),0) + coalesce(SUM(`ot`),0) + coalesce(SUM(`holidays`),0)) * 0.1267) +
    '198.24' +
    IF(SUM(`parking_car_discount`)=175,300,IF(SUM(`parking_car_discount`)=350,300,SUM(`car_count`)*21.43)) +
    IF(SUM(`parking_motorcycle_discount`)=50,300,IF(SUM(`parking_motorcycle_discount`)=150,300,SUM(`motorcycle_count`)*21.43)) AS `total_cost`

    FROM (SELECT 
    idpayments, id_employee, id_period, periods.start,
    nearsol_id, client_id,
    first_name,
    second_name,
    first_lastname,
    second_lastname,
    id_account,
    job,
    (payments.base_complete/2) AS `base`,
    (((payments.productivity_complete-250)/2) + 125) AS `incentive`,
    (((15 - (coalesce(payments.base_hours,0)/8)) - coalesce(payments.sevenths,0)) * (-1)) AS `discounted_days`,
    (coalesce(payments.sevenths,0) * (-1)) AS `sevenths_discounted`,
    ((120 - payments.base_hours) * (-1)) AS `discounted_hours`,
    (((120 - payments.base_hours) * (-1)) * (payments.base_complete/240)) AS `wage_deductions`,
    ((((120 - payments.base_hours) * (-1)) * ((avg(payments.productivity_complete)-250)/240)) - (125 - avg(`decreto`.amount))) AS `incentive_deductions`,
    payments.base AS `base_with_deductions`,
    payments.productivity + avg(`decreto`.amount) AS `productivity_with_deductions`,
    payments.ot_hours,
    payments.ot,
    payments.holidays_hours,
    payments.holidays,
    SUM(`bonuses`.amount) AS `bonuses`,
    SUM(`tresure_hunt`.amount) AS `tresure_hunt_bonus`,
    (SUM(coalesce(`adjustments`.amount,0)) - SUM(coalesce(`adjustments_deb`.amount,0))) AS `adj`,
    (avg(coalesce(`baseCredit`.amount,0)) + avg(coalesce(`prodCredit`.amount,0)) + 
        avg(coalesce(`bonuses`.amount,0)) + avg(coalesce(payments.ot,0)) + 
        avg(coalesce(payments.holidays,0)) + avg(coalesce(`tresure_hunt`.amount,0)) +
        coalesce(avg(`decreto`.amount),0) + SUM(coalesce(`adjustments`.amount,0)) -  SUM(coalesce(`adjustments_deb`.amount,0))) AS `total_income`,
    (SUM(`bus`.amount) * (-1)) AS `bus_discount`,
    (SUM(`parking_car`.amount) * (-1)) AS `parking_car_discount`,
    (SUM(`parking_motorcycle`.amount) * (-1)) AS `parking_motorcycle_discount`,
    (SUM(`igss`.amount) * (-1)) AS `igss_discount`,
    (SUM(`isr`.amount) * (-1)) AS `isr_discount`,
    (SUM(`equipment`.amount) * (-1)) AS `equipment_discount`,
    ((coalesce(SUM(`equipment`.amount),0) + coalesce(SUM(`isr`.amount),0) +
        coalesce(SUM(`igss`.amount),0) + coalesce(SUM(`parking_car`.amount),0) +
        coalesce(SUM(`bus`.amount),0) + coalesce(SUM(`parking_motorcycle`.amount),0)) * (-1)) AS `total_deductions`,
    ROUND(((avg(`baseCredit`.amount) + avg(`prodCredit`.amount) + 
        avg(coalesce(`bonuses`.amount,0)) + coalesce(avg(payments.ot),0) + 
        coalesce(avg(payments.holidays),0) + avg(coalesce(`tresure_hunt`.amount,0)) + 
        SUM(coalesce(`adjustments`.amount,0)) - SUM(coalesce(`adjustments_deb`.amount,0))) - 
        coalesce(avg(`equipment`.amount),0) - coalesce(avg(`isr`.amount),0) - 
        coalesce(avg(`igss`.amount),0) - coalesce(avg(`parking_car`.amount),0) - coalesce(avg(`parking_motorcycle`.amount),0) -
        coalesce(avg(`bus`.amount),0) + coalesce(avg(`decreto`.amount),0)),2) AS `total_payment`,
    COUNT(`parking_car`.iddebits) AS `car_count`,
    COUNT(`parking_motorcycle`.iddebits) AS `motorcycle_count`
    FROM profiles
        INNER JOIN hires ON hires.id_profile = profiles.idprofiles
        INNER JOIN employees ON employees.id_hire = hires.idhires
        INNER JOIN payments ON payments.id_employee = employees.idemployees
        INNER JOIN periods ON periods.idperiods = payments.id_period
        LEFT JOIN credits AS `baseCredit` ON `baseCredit`.id_payment = payments.idpayments AND `baseCredit`.type = 'Salario Base'
        LEFT JOIN credits AS `prodCredit` ON `prodCredit`.id_payment = payments.idpayments AND `prodCredit`.type = 'Bonificacion Productividad'
        LEFT JOIN credits AS `bonuses` ON `bonuses`.id_payment = payments.idpayments
                AND `bonuses`.type != 'Salario Base' 
                AND `bonuses`.type != 'Bonificacion Productividad'
                AND `bonuses`.type!= 'Bonificacion Decreto'
                AND `bonuses`.type NOT LIKE 'Horas De Asueto:%'
                AND `bonuses`.type NOT LIKE 'Auto Ajuste%'
                AND `bonuses`.type NOT LIKE 'Horas Extra Laboradas:%'
        LEFT JOIN credits AS `tresure_hunt` ON `tresure_hunt`.id_payment = payments.idpayments AND `tresure_hunt`.type = 'Tresure Hunt'
        LEFT JOIN credits AS  `adjustments` ON `adjustments`.id_payment = payments.idpayments AND `adjustments`.type LIKE '%Ajuste%'
        LEFT JOIN credits AS `decreto` ON `decreto`.id_payment = payments.idpayments AND `decreto`.type = 'Bonificacion Decreto'
        LEFT JOIN debits AS `adjustments_deb` ON `adjustments_deb`.id_payment = payments.idpayments AND `adjustments_deb`.type LIKE '%Ajuste%'
        LEFT JOIN debits AS `bus` ON `bus`.id_payment = payments.idpayments AND (`bus`.type LIKE '%Monthly Bus%' OR `bus`.type LIKE '%Daily Bus%')
        LEFT JOIN debits AS `parking_car` ON `parking_car`.id_payment = payments.idpayments AND (`parking_car`.type LIKE '%Car Parking%')
        LEFT JOIN debits AS `parking_motorcycle` ON `parking_motorcycle`.id_payment = payments.idpayments AND `parking_motorcycle`.type LIKE '%Motorcycle Parking%'
        LEFT JOIN debits AS `igss` ON `igss`.id_payment = payments.idpayments AND `igss`.type LIKE 'Descuento IGSS%'
        LEFT JOIN debits AS `isr` ON  `isr`.id_payment = payments.idpayments AND `isr`.type LIKE '%ISR%'
        LEFT JOIN debits AS `equipment` ON `equipment`.id_payment = payments.idpayments AND `equipment`.type LIKE '%Equipment Discount%'
    WHERE active = 1  GROUP BY idpayments) AS `tst` WHERE `tst`.start between '$start' AND '$end' AND id_account IN($account) GROUP BY `tst`.id_employee;";
    echo($sql);
    $output = fopen("php://output", "w");
    fputcsv($output, array('#','Code','Avaya','Name','Minimum Wage','Incentive','Days discounted','7th deduction','Discounted hours','Minimum Wage Deductions','Incentive Deductions','Minimum Wage with deductions','Incentive with deductions','Overtime (hours)','Overtime (Q)','Holiday (hours)','Holiday (Q)','Bonuses','Treasure Hunt','Adjustments','Total income','Bus','Parking (Car)','Parking Motorcycle / bicycle','IGSS','ISR','Equipment','Total Deductions','Total Payment','BONUS 13','BONUS 13 BONIF','BONUS 14 ','BONUS 14 BONIF','VACATION RESERVES','VACATION RESERVES BONIF','SEVERANCE RESERVES','EMPLOYER IGSS','HEALTH INSURANCE','PARKING','BUS','TOTAL RESERVES AND FEES','TOTAL COST',));
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            fputcsv($output, $row, ",");
            $i++;
        };
    }else{
        http_response_code(404);
    }
}else{
    echo($sql2);
    echo(mysqli_error($con));
}
fclose($output);
?>