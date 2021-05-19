<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "ReporteDeVacaciones.csv" . '"');
require 'database.php';

echo "\xEF\xBB\xBF";

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$title = ['ID_Nearsol', 'Avaya', 'Puesto', 'Cuenta', 'Nombre', 'Fecha de ingreso', 'Vacaciones acumuladas', 'Vacaciones gozadas', 'Vacaciones disponibles', 'Tipo de ausencia', 'Fecha día gozado', 'Mes que corresponde', 'Dia completo/Medio día'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
$i = 0;

for ($i=0; $i < count($request); $i++) {
  $id_employee = ($request[$i]->idemployees);

  $sql = "SELECT 
    h.nearsol_id, 
    e.client_id, 
    e.job, 
    a.name AS 'cuenta',   
    CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS nombre,  
    e.hiring_date, 
    ROUND(15 * (DATEDIFF(CURDATE(), e.hiring_date) / 365), 0) AS 'acumuladas',
    v3.gozadas AS 'gozadas',
    (ROUND(15 *(DATEDIFF(CURDATE(), e.hiring_date) / 365), 0) - v3.gozadas) AS 'disponibles',
    '' AS 'tipoAusencia',
    v.`date`, 
    0 AS 'mes',
    if(v.count=1,'Complete', 'Middle') AS 'complete'
    FROM employees e  
    inner join hires h ON (e.id_hire = h.idhires)
    inner join profiles p on (h.id_profile = p.idprofiles)
    inner join accounts a on (e.id_account = a.idaccounts)
    inner join hr_processes hp ON (e.idemployees = hp.id_employee)
    inner join vacations v on (hp.idhr_processes = v.id_process)
    inner join (SELECT SUM(v2.`count`) AS 'gozadas', hp2.id_employee from hr_processes hp2 inner join vacations v2 on (hp2.idhr_processes = v2.id_process) where hp2.id_type = 4 and v2.`action` = 'Take' GROUP BY hp2.id_employee) v3 on (e.idemployees = v3.id_employee)
    where v.`action` = 'Take'
    and e.idemployees = $id_employee
    order by v.`date`, h.nearsol_id;";

  if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $req[0] =  $row['nearsol_id'];
        $req[1] =  $row['client_id'];
        $req[2] =  $row['job'];
        $req[3] =  $row['cuenta'];
        $req[4] =  $row['nombre'];
        $req[5] =  $row['hiring_date'];
        $req[6] =  $row['acumuladas'];
        $req[7] =  $row['gozadas'];
        $req[8] =  $row['disponibles'];
        $req[9] =  $row['tipoAusencia'];
        $req[10] = $row['date'];
        $req[11] = $row['mes'];
        $req[12] = $row['complete'];
        
        fputcsv($output, $req, ",");
    };
  }else{
      http_response_code(404);
  }
}
fclose($output);
?>