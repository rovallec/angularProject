<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Content-type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . "igss.txt" . '"');
    require 'database.php';
    /*
    assign file content to a PHP Variable $content
    */

    $user = $_GET['user'];
    $patrono = $_GET['patrono'];
    $address = $_GET['address'];
    $nit_patrono = $_GET['nit_patrono'];
    $patronal_number = $_GET['patronal_number'];
    $date_to_show_g = date("d/m/Y");

    $period = $_GET['period'];

    $sql_period = "SELECT * FROM periods WHERE idperiods = $period";

    if($result = mysqli_query($con,$sql_period)){
        $row = mysqli_fetch_assoc($result);
        $date_start = $row['start'];
        $date_end = date('Y-' . explode("-",$date_start)[1] . '-t');

        $date = explode("-",$date_start);

        $date_obj_start = strtotime($date_start);
        $date_obj_end = strtotime($date_end);

        $start_period_date_dmy = date("d/m/Y", $date_obj_start);
        $end_period_date_dmy = date("d/m/Y", $date_obj_end);

        /*HEADER*/
        echo("2.1.0|$date_to_show_g|$patronal_number|" . $date[1] . "|" . $date[0] . "|$patrono|nit_patrono|$user|0\n");
        echo("[centros]\n");
        echo("1|$patrono|$address|10|||||1|1|749999|\n");
        echo("[tiposplanilla]\n");
        echo("1|Planilla Mensual |C|M|1|749999|N|\n");
        echo("[liquidaciones]\n");
        echo("1|1|$start_period_date_dmy|$end_period_date_dmy|O||\n");
        
        $sql_employees = "SELECT profiles.iggs, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname,
                            ROUND(`base_salary`.`base`,2) AS `base_int`, IF(employees.hiring_date >= LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH))
                            AND employees.hiring_date <= LAST_DAY('$date_start'), DATE_FORMAT(employees.hiring_date, '%d/%m/%Y'), '') AS `hiring`, 
                            IF(`term`.valid_from <= LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH)),
                            DATE_FORMAT(`term`.valid_from, '%d/%m/%Y'), NULL) AS `term`, profiles.nit
                            FROM employees
                            INNER JOIN hires ON hires.idhires = employees.id_hire
                            INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
                            LEFT JOIN (SELECT * FROM terminations
                                    INNER JOIN hr_processes ON terminations.id_process = hr_processes.idhr_processes AND hr_processes.id_type = 8)
                                    AS `term` ON `term`.id_employee = employees.idemployees
                            LEFT JOIN (SELECT ROUND(SUM(credits.amount),2) AS `base`, id_employee FROM credits
                                    INNER JOIN payments ON payments.idpayments = credits.id_payment
                                    INNER JOIN periods ON periods.idperiods = payments.id_period
                                                AND (credits.type = 'Salario Base' OR credits.type LIKE '%Horas Extra Laboradas%' OR credits.type LIKE '%Horas De Asueto%')
                                    WHERE periods.start = DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH) 
                                    OR periods.end = LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH)) GROUP BY id_employee)
                                    AS `base_salary` ON `base_salary`.id_employee = employees.idemployees
                            WHERE (active = 1 OR `term`.valid_from >= DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH))
                            AND employees.society LIKE '%$patrono%'";
        if($res2 = mysqli_query($con, $sql_employees)){
            echo("[empleados]\n");
            while($row2 = mysqli_fetch_assoc($res2)){
                $igss = $row2['iggs'];
                $first_name = strtoupper($row2['first_name']);
                $second_name = strtoupper($row2['second_name']);
                $first_lastname = strtoupper($row2['first_lastname']);
                $second_lastname = strtoupper($row2['second_lastname']);
                $base = number_format($row2['base_int'],2,".",",");
                $hiring = $row2['hiring'];
                $term = $row2['term'];
                $nit = $row2['nit'];

                echo("1|$igss|$first_name|$second_name|$first_lastname|$second_lastname||$base|$hiring|$term|1|$nit|4223|P||\n");
            };

        }

        $sql_suspension = "SELECT profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, profiles.iggs,
                        DATE_FORMAT(leaves.start, '%d/%m/%Y') AS `start`,
                        DATE_FORMAT(leaves.end, '%d/%m/%Y') AS `end`
                        FROM leaves
                        INNER JOIN hr_processes ON leaves.id_process = hr_processes.idhr_processes
                        INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
                        INNER JOIN hires ON hires.idhires = employees.id_hire
                        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
                        WHERE (`motive` = 'IGSS Unpaid' OR `notes` LIKE '%IGSS%') AND 
                        (leaves.start >= DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH) 
                        AND leaves.end <= LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH)))
                        OR (leaves.start <= DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH) 
                        AND leaves.end BETWEEN '2021-05-01' AND LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH)))
                        AND hr_processes.status != 'DISMISSED' AND employees.society LIKE '%$patrono%';";

        echo("[suspendidos]\n");

        if($res3 = mysqli_query($con,$sql_suspension)){
            while($row3 = mysqli_fetch_assoc($res3)){
                $igss_sus = $row3['iggs'];
                $first_name_sus = strtoupper($row3['first_name']);
                $second_name_sus = strtoupper($row3['second_name']);
                $first_lastname_sus = strtoupper($row3['first_lastname']);
                $second_lastname_sus = strtoupper($row3['second_lastname']);
                $leave_start = $row3['start'];
                $leave_end = $row3['end'];
                echo("1|$igss_sus|$first_name_sus|$second_name_sus|$first_lastname_sus|$second_lastname_sus||$leave_start|$leave_end|\n");
            }
        }

        $sql_lic = "SELECT profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, profiles.iggs,
                    DATE_FORMAT(leaves.start, '%d/%m/%Y') AS `start`,
                    DATE_FORMAT(leaves.end, '%d/%m/%Y') AS `end`
                    FROM leaves
                    INNER JOIN hr_processes ON leaves.id_process = hr_processes.idhr_processes
                    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
                    INNER JOIN hires ON hires.idhires = employees.id_hire
                    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
                    WHERE (`motive` = 'Leave Of Absence Unpaid' OR `motive` = 'VTO Unpaid' OR `notes` LIKE '%VTO%' OR `notes` LIKE '%LOA%')
                    AND ((leaves.start >= DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH) 
                    AND leaves.end <= LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH)))
                    OR (leaves.start <= DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH) 
                    AND leaves.end BETWEEN DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH) 
                    AND LAST_DAY(DATE_ADD(DATE_ADD(LAST_DAY('$date_start'),INTERVAL 1 DAY),INTERVAL -1 MONTH))))
                    AND hr_processes.status != 'DISMISSED' AND employees.society LIKE '%$patrono%';";

        echo("[licencias]\n");

        if($res4 = mysqli_query($con,$sql_lic)){
            while($row4 = mysqli_fetch_assoc($res4)){
                $igss_lic = $row4['iggs'];
                $first_name_lic = strtoupper($row4['first_name']);
                $second_name_lic = strtoupper($row4['second_name']);
                $first_lastname_lic = strtoupper($row4['first_lastname']);
                $second_lastname_lic = strtoupper($row4['second_lastname']);
                $leave_start_lic = $row4['start'];
                $leave_end_lic = $row4['end'];
                echo("1|$igss_lic|$first_name_lic|$second_name_lic|$first_lastname_lic|$second_lastname_lic||$leave_start_lic|$leave_end_lic|\n");
            }
        }

        echo("[juramento]\n");
        echo("BAJO MI EXCLUSIVA Y ABSOLUTA RESPONSABILIDAD, DECLARO QUE LA INFORMACION QUE AQUI CONSIGNO ES FIEL Y EXACTA, QUE ESTA PLANILLA INCLUYE A TODOS LOS TRABAJADORES QUE ESTUVIERON A MI SERVICIO Y QUE SUS SALARIOS SON LOS EFECTIVAMENTE DEVENGADOS, DURANTE EL MES ARRIBA INDICADO\n");
        echo("[finplanilla]");

    }
?>
