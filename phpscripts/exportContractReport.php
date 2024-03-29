<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reporteDeContratos.csv" . '"');
require 'database.php';
include 'funcionesVarias.php';

$marital_status = '';

echo "\xEF\xBB\xBF";
$i = 0;

$sql = "SELECT
@i := @i + 1 AS 'No.',
a.name AS account,
w.name as wave,
p2.name,
cd.address,
cd.city AS neighborhood,
p.profesion AS title,
p.gender,
p.nationality,
p.dpi,
p.day_of_birth AS birthday,
(select TIMESTAMPDIFF(year, p.day_of_birth, now())) AS age,
p.marital_status,
e.hiring_date,
DAY(e.hiring_date) AS hiring_day,
MONTH(e.hiring_date) AS hiring_month,
YEAR(e.hiring_date) AS hiring_year,
'HR' AS revised,
0 as number_contract,
YEAR('contract_date') AS contract_year,
'Salario base en letras' AS salary_letter,
e.base_payment,
'Bonificación en letras' AS bonus_letter,
(e.productivity_payment - 250.00) AS productivity_payment,
250.00 AS '78-89',
'dos cientos cincuenta quetzales exactos' as '78-89_letter',
e.job,
'funciones varias' as 'functions',
'Salario total en letras' AS total_letter,
e.base_payment + e.productivity_payment AS total_salary
FROM employees e
INNER JOIN hires h ON h.idhires = e.id_hire
INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) AS name, p1.idprofiles FROM profiles p1) p2 ON (p2.idprofiles = h.id_profile)
INNER JOIN profiles p ON (h.id_profile = p.idprofiles)
INNER JOIN accounts a ON a.idaccounts = e.id_account
INNER JOIN users u ON u.idUser = e.reporter
INNER JOIN waves w ON (h.id_wave = w.idwaves)
LEFT JOIN contact_details AS cd ON (p.idprofiles = cd.id_profile)
LEFT JOIN education_details ed ON (p.idprofiles = ed.ideducation_details)
CROSS JOIN (SELECT @i := 0) r
ORDER BY a.name ASC ;";


$title = ['NO.', 'Account', 'Wave', 'Nombre Completo', 'Dirección', 'Vecindad', 'Título', 'Genero', 'nacionalidad', 'DPI', 'Letras', 'Fecha de Nacimineto', 'Edad #', 'Edad (Letras)', 'Estado Civil', 'Fecha Ingreso', 'dia', 'mes', 'año', 'dia', 'mes', 'año', 'Revisados', 'NUMERO DE CONTRATO', 'CONTRACT YEAR', 'SALARIO BASE', 'SALARIO BASE #', 'BONIFICACION INCENTIVO', '78-89', 'Bono eficiencia #', 'bono ef letras', 'puesto', 'funciones', 'Salario Total', 'Salario Tot #'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
if($result = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($result)){
    $exportRow[0] = $row['No.'];
    $exportRow[1] = $row['account'];
    $exportRow[2] = $row['wave'];
    $exportRow[3] = $row['name'];
    $exportRow[4] = $row['address'];
    $exportRow[5] = $row['neighborhood'];
    $exportRow[6] = $row['title'];
    switch ($row['gender']) {
      case 'FEMALE':
        $gender = 'Femenino';
      case 'MALE':
        $gender = 'Masculino';
        break;
    }
    $exportRow[7] = $gender;
    $exportRow[8] = $row['nationality'];
    $exportRow[9] = $row['dpi'];
    $exportRow[10] = dpi_letter($row['dpi']);
    $exportRow[11] = $row['birthday'];
    $exportRow[12] = $row['age'];
    $exportRow[13] = number_letter($row['age']);
    if($row['marital_status'] == 'SINGLE'){
      $marital_status = 'Soltero';
    }
    if($row['marital_status'] == 'MARRIED'){
      $marital_status = 'Casado';
    }
    $exportRow[14] = $marital_status;
    $exportRow[15] = $row['hiring_date'];
    $exportRow[16] = $row['hiring_day'];
    $exportRow[17] = $row['hiring_month'];
    $exportRow[18] = $row['hiring_year'];
    $exportRow[19] = number_letter($row['hiring_day']);
    $exportRow[20] = getMonth($row['hiring_month']);
    $exportRow[21] = number_letter($row['hiring_year']);
    $exportRow[22] = $row['revised'];
    $exportRow[23] = $row['number_contract'];
    $exportRow[24] = $row['contract_year'];

    //$base_n_init = explode(".", number_format(((float)$row['base_payment']),2));
    //$base_n_int_l = $f->format($base_n_init[0]);
    //$base_n_cent_l = $f->format(number_format($base_n_init[1],2));

    $exportRow[25] = number_letter_quetzales(number_format((float)$row['base_payment'],2));
    $exportRow[26] = number_format((float)$row['base_payment'],2);
    $exportRow[27] = number_letter_quetzales_exactos(number_format((float)$row['78-89'],2));
    $exportRow[28] = number_format(((float)$row['78-89']),2);
    $exportRow[29] = number_letter_quetzales(number_format((float)$row['productivity_payment'],2));
    $exportRow[30] = number_format((float)$row['productivity_payment'],2);
    $exportRow[31] = $row['job'];
    $exportRow[32] = $row['functions'];
    $exportRow[33] = number_letter_quetzales_exactos(number_format((float)$row['total_salary'],2));
    $exportRow[34] = number_format((float)$row['total_salary'],2);
    fputcsv($output, $exportRow,",");
    $i++;
  };
} else {
  http_response_code(404);
}
fclose($output);
?>
