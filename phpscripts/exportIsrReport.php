    <?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . "ProyeccionISR.csv" . '"');
    require 'database.php';
    echo "\xEF\xBB\xBF";

    $start = date("Y") . "-01-01";
    $end = $_GET['end'];

    $monthly_mult = 0;

    $today_date = new DateTime(date("Y-m-d"));

    $ag_date = new DateTime((date("Y")) . "-12-01");
    $bn_date = new DateTime((date("Y")) . "-07-01");

    if(date("d", strtotime($end)) <= 15){
        $monthly_mult = 1;
    }else{
        $monthly_mult = 0.5;
    }

    $sql = "SELECT employees.idemployees, profiles.nit, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, coalesce(`cmp_base`, 0) AS `base`, coalesce(`cmp_productivity`,0) AS `productivity`,
    coalesce(`crd`.`amnt`,0) AS `bonuses`,
    '250.00' AS `decreto`, coalesce(`ot`,0)  AS `over_time`, coalesce(`rise_amount`,0) AS `rises`, employees.hiring_date, coalesce(employees.indemnizations,0) AS `indemnization`, coalesce(employees.retentions,0) AS `retention`,
    coalesce(`real_base`,0) AS `print_base`, coalesce(`real_productivity`,0) AS `print_productivity`, SUM(coalesce(`b_decreto`.`b_amt`,0)) AS `decreto_acumulado`, SUM(coalesce(formeremployer.aguinaldo, 0)) AS `ex_aguinaldo`,
    SUM(coalesce(formeremployer.bono14,0)) AS `ex_bono14`, SUM(coalesce(formeremployer.igss,0)) AS `ex_igss`, SUM(coalesce(formeremployer.taxpendingpayment,0)) AS `ex_tax`, SUM(formeremployer.indemnization) AS `ex_indemnizations`,
    SUM(coalesce(`adj`.`amnt`, 0))  AS `adjustments`, SUM(coalesce(`hld`.`amnt`,0)) AS `hol`
    FROM employees
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        LEFT JOIN (SELECT group_concat(new_salary) AS `rise_amount`, id_employee FROM hr_processes 
                        INNER JOIN rises ON rises.id_process = hr_processes.idhr_processes WHERE hr_processes.id_type = 11 GROUP BY id_employee) AS `rise` ON `rise`.id_employee = employees.idemployees
        LEFT JOIN (SELECT avg(payments.base_complete) AS `cmp_base`, avg(payments.productivity_complete) AS `cmp_productivity`, SUM(payments.base) AS `real_base`, SUM(payments.productivity) AS `real_productivity`, SUM(ot) AS `ot`, id_employee FROM payments
                        INNER JOIN periods ON periods.idperiods = payments.id_period AND periods.start BETWEEN '$start' AND '$end'
                GROUP BY id_employee) AS `pay` ON `pay`.id_employee = employees.idemployees
        LEFT JOIN (SELECT SUM(credits.amount) AS `b_amt`, id_employee
                FROM credits INNER JOIN payments ON payments.idpayments = credits.id_payment
                WHERE credits.type = 'Bonificacion Decreto'
                GROUP BY id_employee) AS `b_decreto` ON `b_decreto`.id_employee = employees.idemployees
        LEFT JOIN (SELECT SUM(credits.amount) AS `amnt`, id_employee
                    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
                    WHERE credits.type NOT IN('Salario Base', 'Bonificacion Productividad', 'Bonificacion Decreto') 
                        AND credits.type NOT LIKE '%Ajuste%' AND credits.type NOT LIKE '%Horas Extra Laboradas:%' 
                        AND credits.type NOT LIKE '%Horas De Asueto:%' GROUP BY id_employee) AS `crd` ON `crd`.id_employee = employees.idemployees
        LEFT JOIN (SELECT SUM(credits.amount) AS `amnt`, id_employee
                    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
                    WHERE credits.type LIKE '%Ajustes%'
                        GROUP BY id_employee) AS `adj` ON `adj`.id_employee = employees.idemployees
        LEFT JOIN (SELECT SUM(credits.amount) AS `amnt`, id_employee
                    FROM credits INNER JOIN payments on payments.idpayments = credits.id_payment 
                    WHERE credits.type LIKE '%Horas de Asueto:%'
                        GROUP BY id_employee) AS `hld` ON `hld`.id_employee = employees.idemployees
        LEFT JOIN formeremployer ON formeremployer.id_employee = employees.idemployees
    WHERE active = 1 GROUP BY idemployees;";

    $output = fopen("php://output", "w");
    $tittle = ['NIT empleado', 'Sueldos', 'Horas Extras', 'Bono Decreto 37-2001', 'Otras Bonificaciones', 'Comisiones', 'Propinas', 'Aguinaldo', 'Bono Anual de trabajadores (14)', 'Viáticos', 'Gasto de representación', 'Dietas', 'Gratificaciones', 'Remuneraciones', 'Prestaciones IGSS', 'Otros', 'Indemnizaciones o pensiones por causa de muerte', 'Indemnizaciónes por tiempo servido', 'Remuneraciones de los diplomáticos', 'Gastos de representación y viáticos comprobables', 'Aguinaldo', 'Bono Anual de trabajadores (14)', 'Cuotas IGSS  y Otros planes de seguridad social'];
    fputcsv($output, $tittle);
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            if(date($row['hiring_date']) <= date((date("Y")-1) . "-12-01")){
                $a_date = new DateTime((date("Y")-1) . "-12-01");
                $a_diff = $a_date->diff($ag_date);
                $a_days = $a_diff->format("%a");
            }else{
                $a_date = new DateTime($row['hiring_date']);
                $a_diff = $ag_date->diff($a_date);
                $a_days = $a_diff->format("%a");
            };
            if(date($row['hiring_date']) <= date((date("Y")-1) . "-07-01")){
                $b_date = new DateTime((date("Y")-1) . "-07-01");
                $b_diff = $bn_date->diff($b_date);
                $b_days = $b_diff->format("%a");
            }else{
                $b_date = new DateTime($row['hiring_date']);
                $b_diff = $bn_date->diff($b_date);
                $b_days = $b_diff->format("%a");
            };
            $isr[0] = str_replace("-", "",$row['nit']);
            $isr[1] = number_format($row['base'] * (12 - date("m",strtotime($end))) + $row['print_base'] + ($row['base'] * $monthly_mult));
            $isr[2] = $row['over_time'] + $row['hol'];
            $isr[3] = number_format(((250 * (12 - date("m",strtotime($end)))) + ($row['decreto_acumulado']) + ($monthly_mult * 250)),2);
            $isr[4] = number_format((($row['productivity']) * (12 - date("m",strtotime($end))) + (($row['productivity']) * $monthly_mult) + $row['print_productivity'] + $row['bonuses'] + (($row['productivity'] + 250) * ($b_days/365)) + (($row['productivity'] + 250) * ($a_days/365)) + ($row['adjustments'])),2);
            /*$isr[4] = (($row['productivity']) . "*" . (12 - date("m",strtotime($end))) . "+" . (($row['productivity']) * $monthly_mult) 
                      . '+' . $row['print_productivity'] . '+' . $row['bonuses'] . "+" . (($row['productivity'] + 250) * ($b_days/365)) . "+" . 
                      (($row['productivity'] + 250) * ($a_days/365)) . "+" . ($row['adjustments']));*/
            $isr[5] = '0';
            $isr[6] = '0';
    //////////////////////////////////////////////////AGUINALDO//////////////////////////////////////////////////////////////
            $isr[7] = number_format(((($row['base']) * ($a_days/365)) + $row['ex_aguinaldo']),2);
    //////////////////////////////////////////////////BONO 14//////////////////////////////////////////////////////////////
            $isr[8] = number_format(((($row['base']) * ($b_days/365)) + $row['ex_bono14']),2);
            $isr[9] = '0';
            $isr[10] = '0';
            $isr[11] = '0';
            $isr[12] = '0';
            $isr[13] = '0';
            $isr[14] = '0';
            $isr[15] = $row['ex_indemnizations'];
            $isr[16] = '0';
            $isr[17] = $row['ex_indemnizations'];
            $isr[18] = '0';
            $isr[19] = '0';
            $isr[20] = number_format(((($row['base']) * ($a_days/365)) + $row['ex_aguinaldo']),2);
            $isr[21] = number_format(((($row['base']) * ($b_days/365)) + $row['ex_bono14']),2);
            $isr[22] = number_format(((($row['base'] * (12 - date("m",strtotime($end)))) + $row['print_base'] + $row['over_time'] + $row['hol'] + ($monthly_mult * $row['base']))*0.0483),2);
            fputcsv($output, $isr, ",");
        };
    }else{
        http_response_code(404);
    }
    fclose($output);
    ?>