<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->idperiods);

$sql = " SELECT DISTINCT
          e.idemployees,
          h.nearsol_id,
          e.client_id, /* avaya_id */
          e.hiring_date,  
          p2.name,
          u.user_name AS rep,
          a.name AS acc_name,
          r.approved_date,
          e.base_payment,
          e.productivity_payment,
          (e.base_payment + e.productivity_payment) as Total
        FROM employees e
        INNER JOIN hires h ON h.idhires = e.id_hire
        INNER JOIN accounts a ON a.idaccounts = e.id_account
        INNER JOIN profiles p ON p.idprofiles = h.id_profile
        INNER JOIN users u ON u.idUser = e.reporter
        INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = p.idprofiles)
        INNER JOIN contact_details cd on (p.idprofiles = cd.id_profile)
        left join hr_processes hp on (e.idemployees = hp.id_employee)
        left join rises r on (hp.idhr_processes = r.id_process);";

if($result = mysqli_query($con, $sql)){
  $i = 0;
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['result'] = $row['result'];
    $res[$i]['client_id'] = $row['client_id'];
    $res[$i]['content'] = $row['content'];
    $res[$i]['idpaystub_deatils'] = $row['idpaystub_deatils'];
    $res[$i]['email'] = $row['email'];
		$res[$i]['society'] = $row['society'];
		$res[$i]['account'] = $row['account'];
		$res[$i]['employeer_nit'] = $row['employeer_nit'];
		$res[$i]['idpayments'] = $row['idpayments'];
		$res[$i]['start'] = $row['start'];
		$res[$i]['end'] = $row['end'];
		$res[$i]['nit'] = $row['nit'];
		$res[$i]['type'] = $row['type'];
		$res[$i]['number'] = $row['number'];
		$res[$i]['iggs'] = $row['iggs'];
		$res[$i]['user_name'] = $row['user_name'];
		$res[$i]['bank'] = $row['bank'];
		$res[$i]['days_of_period'] = $row['days_of_period'];
		$res[$i]['discounted_days'] = number_format($row['discounted_days'],2);
		$res[$i]['ot_hours'] = number_format($row['ot_hours'],2);
		$res[$i]['discounted_hours'] = number_format($row['discounted_hours'],2);
		$res[$i]['holidays_hours'] = number_format($row['holidays_hours'],2);
		$res[$i]['base'] = number_format($row['base'],2);
		$res[$i]['ot'] = number_format($row['ot'],2);
		$res[$i]['holidays'] = number_format($row['holidays'],2);
		$res[$i]['decreto'] = number_format($row['decreto'],2);
		$res[$i]['bonificaciones'] = number_format($row['bonificaciones'],2);
		$res[$i]['eficiencia'] = number_format($row['eficiencia'],2);
		$res[$i]['ajustes'] = number_format($row['ajustes'],2);
		$res[$i]['igss_amount'] = number_format($row['igss_amount'],2);
		$res[$i]['otras_deducciones'] = number_format($row['otras_deducciones'],2);
		$res[$i]['anticipos'] = number_format($row['anticipos'],2);
		$res[$i]['isr'] = number_format($row['isr'],2);
		$res[$i]['employee_name'] = $row['employee_name'];
		$res[$i]['nearsol_id'] = $row['nearsol_id'];
		$res[$i]['parqueo'] = number_format($row['parqueo'],2);
		$res[$i]['total_cred'] = number_format($row['credits'] + $row['anticipos_cred_amount'],2);
		$res[$i]['total_deb'] = number_format($row['debits'],2);
		$res[$i]['liquido'] = ($row['credits'] - $row['debits']);
    $i++; 
  };
  echo json_encode($res);
}else{
  echo json_encode($sql);
  http_response_code(404);
}

?>