<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
if ($id==0) {
  $sqlid = ";";
} else {
  $sqlid = "AND u.id_profile = $id LIMIT 100;";
}

$i = 0;
$ajustes = [];
$sql = "SELECT
          aa.idattendence_adjustemnt,
          aa.id_attendence,
          aa.id_justification,
          aa.time_before,
          aa.time_after,
          aa.amount,
          e.state,
          aa.start,
          aa.end,
          aj.id_process,
          aj.reason,
          u.username as user_name,
          hp.id_employee,
          hp.id_type,  
          hp.id_department,
          hp.`date`,
          hp.notes,
          hp.status,
          at.date AS attdate,
          p2.name,
          h.nearsol_id,
          a.idaccounts as acn,
          hp.time,
          hp.status
        FROM users u 
        INNER JOIN employees e ON u.idUser = e.reporter  
        INNER JOIN hr_processes hp ON hp.id_employee = e.idemployees 
        INNER JOIN attendence_justifications aj ON hp.idhr_processes = aj.id_process
        INNER JOIN attendence_adjustemnt aa ON aj.idattendence_justifications = aa.id_justification
        INNER JOIN attendences at ON at.idattendences = aa.id_attendence
        INNER JOIN hires h ON h.idhires = e.id_hire
        INNER JOIN profiles p ON p.idprofiles = h.id_profile
        INNER JOIN (select UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = h.id_profile)
        LEFT JOIN accounts a ON a.idaccounts = e.id_account
        WHERE  hp.id_type = '2'
        AND hp.status = 'REQUESTED' " 
        . $sqlid;

if($result = mysqli_query($con,$sql)){
  while($res = mysqli_fetch_assoc($result)){
    $ajustes[$i]['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
    $ajustes[$i]['id_attendence'] = $res['id_attendence'];
    $ajustes[$i]['id_justification'] = $res['id_justification'];
    $ajustes[$i]['time_before'] = $res['time_before'];
    $ajustes[$i]['time_after'] = $res['time_after'];
    $ajustes[$i]['amount'] = $res['amount'];
    $ajustes[$i]['state'] = $res['state'];
    $ajustes[$i]['start'] = $res['start'];
    $ajustes[$i]['end'] = $res['end'];
    $ajustes[$i]['id_process'] = $res['id_process'];
    $ajustes[$i]['reason'] = $res['reason'];
    $ajustes[$i]['id_user'] = $res['user_name'];
    $ajustes[$i]['id_employee'] = $res['id_employee'];
    $ajustes[$i]['id_type'] = $res['id_type'];
    $ajustes[$i]['id_department'] = $res['id_department'];
    $ajustes[$i]['date'] = $res['date'];
    $ajustes[$i]['notes'] = $res['notes'];
    $ajustes[$i]['status'] = $res['status'];
    $ajustes[$i]['attendance_date'] = $res['attdate'];
    $ajustes[$i]['name'] = $res['name'];
    $ajustes[$i]['nearsol_id'] = $res['nearsol_id'];
    $ajustes[$i]['error'] = "SUCCESS";
    $ajustes[$i]['account'] = $res['acn'];
    $ajustes[$i]['dateTime'] = $res['time'];
    $i++;
  }
  echo(json_encode($ajustes));
} else {
  echo (json_encode($sql));
}

?>
