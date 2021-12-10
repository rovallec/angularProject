<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
if ($id==0) {
  $id = "";
} else {
  $id = "AND hp.id_employee = $id;";
}

$i = 0;
$adjustes = [];
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
          hp.time
        FROM attendence_adjustemnt aa
        INNER JOIN attendences at ON at.idattendences = aa.id_attendence
        INNER JOIN attendence_justifications aj ON aj.idattendence_justifications = aa.id_justification
        INNER JOIN hr_processes hp ON hp.idhr_processes = aj.id_process
        INNER JOIN employees e ON e.idemployees = hp.id_employee
        INNER JOIN hires h ON h.idhires = e.id_hire
        INNER JOIN profiles p ON p.idprofiles = h.id_profile
        INNER JOIN (select UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = h.id_profile)
        LEFT JOIN accounts a ON a.idaccounts = e.id_account
        LEFT JOIN users u ON u.idUser = hp.id_user 
        WHERE hp.status = 'REQUESTED'
        AND hp.id_type = '2' " 
        . $id;

echo($sql);

if($result = mysqli_query($con,$sql)){
  while($res = mysqli_fetch_assoc($result)){
    $adjustes[$i]['idattendence_adjustemnt'] = $res['idattendence_adjustemnt'];
    $adjustes[$i]['id_attendence'] = $res['id_attendence'];
    $adjustes[$i]['id_justification'] = $res['id_justification'];
    $adjustes[$i]['time_before'] = $res['time_before'];
    $adjustes[$i]['time_after'] = $res['time_after'];
    $adjustes[$i]['amount'] = $res['amount'];
    $adjustes[$i]['state'] = $res['state'];
    $adjustes[$i]['start'] = $res['start'];
    $adjustes[$i]['end'] = $res['end'];
    $adjustes[$i]['id_process'] = $res['id_process'];
    $adjustes[$i]['reason'] = $res['reason'];
    $adjustes[$i]['id_user'] = $res['user_name'];
    $adjustes[$i]['id_employee'] = $res['id_employee'];
    $adjustes[$i]['id_type'] = $res['id_type'];
    $adjustes[$i]['id_department'] = $res['id_department'];
    $adjustes[$i]['date'] = $res['date'];
    $adjustes[$i]['notes'] = $res['notes'];
    $adjustes[$i]['status'] = $res['status'];
    $adjustes[$i]['attendance_date'] = $res['attdate'];
    $adjustes[$i]['name'] = $res['name'];
    $adjustes[$i]['nearsol_id'] = $res['nearsol_id'];
    $adjustes[$i]['error'] = "SUCCESS";
    $adjustes[$i]['account'] = $res['acn'];
    $adjustes[$i]['dateTime'] = $res['time'];
    $i++;
  }
} else {
  echo (json_encode($sql));
}
echo(json_encode($adjustes));


?>
