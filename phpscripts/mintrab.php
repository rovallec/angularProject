<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$result = [];
$i = 0;
$date = date("d/m/Y");
$dt = explode("/",$date);
$mn = [
    'enero',
    'febrero',
    'marzo',
    'abril',
    'mayo',
    'junio',
    'julio',
    'agosto',
    'septiembre',
    'octubre',
    'noviembre',
    'diciembre'
];
$str_date = 'Guatemala ' . $dt[0] . ' de ' . $mn[parse_str($dt[1]) + 1] . " de " . $dt[2];

echo("
<html>
    <div style='margin-left:60px'>
        <table style='width:100%'>
            <tr>
                <td><img src='http://172.18.2.45/assets/Nearsol.png' style='height:60px; width:250px'></td>
                <td style='width:70%'></td>
                <td>$str_date</td>
            </tr>
            <tr>
                <td colspan='3'><b>Ministerio de Trabajo y Previsión Social</b></td>
            </tr>
            <tr>
                <td colspan='3'><b>Inspección General de Trabajo</b></td>
            </tr>
            <tr>
                <td colspan='3'><b>Inspector General de Trabajo</b></td>
            </tr>
            <tr style='height:25px'>
            </tr>
            <tr>
                <td colspan='3'><b>Estimado Inspector General de Trabajo:</b></td>
            </tr>
            <tr style='height:10px'>
            </tr>
            <tr>
                <td colspan='3'>Es un gusto saludarlo nuevamente y le deseo éxitos en sus labores cotidianas.</td>
            </tr>
            <tr>
                <td colspan='3'>El motivo de la presente es para informarle lo siguiente:</td>
            <tr>
            <tr style='height:10px'>
            </tr>
            <tr>
                <td colspan='3'>De acuerdo a los artículos 76, 77 y 78 del código de trabajo Decreto 1441, se le notificó a los siguientes trabajadores la terminación de contrato de trabajo y faltas bajo las siguientes condiciones:</td>
            </tr>
            <tr style='height:10px'>
            </tr>
            <tr>
                    <table style='width:100%;border-collapse:collapse;border:solid 2px black'>
                        <tr style='width:100%'>
                            <td style='border:solid 2px black; text-align:center'><b>Nombre Completo</b></td>
                            <td style='border:solid 2px black; text-align:center'><b>Tipo de Sansión</b></td>
                            <td style='border:solid 2px black; text-align:center'><b>Fecha</b></td>
                            <td style='border:solid 2px black; text-align:center'><b>Descripción</b></td>
                        </tr>
");

$rw1 = '';
$rw2 = '';
$rw3 = '';
$rw4 = '';

$sql = "SELECT `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`,`id_employee`, `users`.*, `hires`.`id_profile`, `hires`.*, `suspensions`.*, `disciplinary_requests`.*, `hr_processes`.*, `disciplinary_processes`.*, `audiences`.`date` AS `audience_date`, `audiences`.`time`, `audiences`.`comments`, `audiences`.`status` AS `audience_status` FROM `disciplinary_requests` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `disciplinary_requests`.`id_process` LEFT JOIN `disciplinary_processes` ON `disciplinary_processes`.`id_request` = `disciplinary_requests`.`iddisciplinary_requests` LEFT JOIN `audiences` ON `audiences`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `suspensions` ON `suspensions`.`id_disciplinary_process` = `disciplinary_processes`.`iddisciplinary_processes` LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`;";
$sql2 = "SELECT * FROM `terminations` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `terminations`.`id_process` LEFT JOIN `employees` ON `employees`.`idemployees` = `hr_processes`.`id_employee` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`;";
if($res1 = mysqli_query($con, $sql)){
    if($res2 = mysqli_query($con,$sql2)){
        while($row = mysqli_fetch_assoc($res1)){
            $rw1 = $row['first_name'] . ' ' . $row['second_name'] . ' ' . $row['first_lastname'] . ' ' . $row['second_lastname'];
            $rw2 = $row['dp_grade'];
            $rw3 = $row['imposition_date'];
            $rw4 = $row['type'];
            echo("
                <tr>
                    <td style='border:solid 1px black; text-align:center'>$rw1</td>
                    <td style='border:solid 1px black; text-align:center'>$rw2</td>
                    <td style='border:solid 1px black; text-align:center'>$rw3</td>
                    <td style='border:solid 1px black; text-align:center'>$rw4</td>
                </tr>
            ");
        }

        while($row2 = mysqli_fetch_assoc($res2)){
            $rw1 = $row2['first_name'] . ' ' . $row2['second_name'] . ' ' . $row2['first_lastname'] . ' ' . $row2['second_lastname'];
            $rw2 = $row2['kind'];
            $rw3 = $row2['valid_from'];
            $rw4 = $row2['reason'];
            echo("
                <tr>
                    <td style='border:solid 1px black; text-align:center'>$rw1</td>
                    <td style='border:solid 1px black; text-align:center'>$rw2</td>
                    <td style='border:solid 1px black; text-align:center'>$rw3</td>
                    <td style='border:solid 1px black; text-align:center'>$rw4</td>
                </tr>
            ");
        }
    }
}

echo(
    '
        </table>
        </tr>
        </table>
        </div>
        </html>
    '
)
?>;
