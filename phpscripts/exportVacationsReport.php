<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$i = 0;
$c = 0;

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
        $req[$c][0] =  $row['nearsol_id'];
        $req[$c][1] =  $row['client_id'];
        $req[$c][2] =  $row['job'];
        $req[$c][3] =  $row['cuenta'];
        $req[$c][4] =  $row['nombre'];
        $req[$c][5] =  $row['hiring_date'];
        $req[$c][6] =  $row['acumuladas'];
        $req[$c][7] =  $row['gozadas'];
        $req[$c][8] =  $row['disponibles'];
        $req[$c][9] =  $row['tipoAusencia'];
        $req[$c][10] = $row['date'];
        $req[$c][11] = $row['mes'];
        $req[$c][12] = $row['complete'];
        $i++;
    };
    echo(json_encode($req));
  }else{
      http_response_code(404);
  }
}
?>