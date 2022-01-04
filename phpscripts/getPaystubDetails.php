<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->idperiods);

$sql = "SELECT paystub_details.result, employees.client_id, paystub_details.idpaystub_deatils, paystub_details.content, contact_details.email AS `email`, employees.society, accounts.name AS `account`, if(employees.society='NEARSOL, S.A.', '10305064-7', '0000000-0') AS `employeer_nit`, payments.idpayments, periods.start, periods.end, hires.nearsol_id, profiles.nit,
        payment_methods.type, payment_methods.number, profiles.iggs, users.user_name, payment_methods.bank, 15 AS `days_of_period`, payroll_values.discounted_days, payments.ot_hours, payroll_values.discounted_hours,
        payments.holidays_hours, payments.base, payments.ot, payments.holidays, `decreto`.amount AS `decreto`, (payments.credits - payments.base - coalesce(payments.ot,0) - coalesce(payments.holidays,0) -
        coalesce(`decreto`.amount,0) - coalesce(`eficiencia`.amount,0) - coalesce(`ajustes`.amount,0)) AS `bonificaciones`, `eficiencia`.amount AS `eficiencia`, `ajustes`.amount AS `ajustes`, `igss`.amount AS `igss_amount`, 
        `parqueo`.amount AS `parqueo`, (payments.debits - coalesce(`igss`.amount,0) - coalesce(`parqueo`.amount,0) - coalesce(`anticipos`.amount,0) - `isr`.amount) AS `otras_deducciones`,
        (coalesce(`anticipos`.amount,0) + (-1*coalesce(`anticipos_cred`.amount,0))) AS `anticipos`, `isr`.amount AS `isr`,
        CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee_name`, payments.credits, payments.debits, (-1*`anticipos_cred`.amount) AS `anticipos_cred_amount`
        FROM payments
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN payment_methods ON payment_methods.active = 1 AND payment_methods.id_employee = payments.id_employee
        INNER JOIN accounts ON accounts.idaccounts = employees.id_account
        INNER JOIN periods ON periods.idperiods = payments.id_period
        INNER JOIN users ON users.idUser = employees.reporter
        INNER JOIN payroll_values ON payroll_values.id_payment = payments.idpayments
        INNER JOIN contact_details ON contact_details.id_profile = profiles.idprofiles
        LEFT JOIN paystub_details ON paystub_details.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Bonos Diversos Nearsol%' GROUP BY id_payment) AS `eficiencia` ON `eficiencia`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Bonificacion Decreto%' GROUP BY id_payment) AS `decreto` ON `decreto`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Ajuste%' AND amount > 0 GROUP BY id_payment) AS `ajustes` ON `ajustes`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%IGSS%' GROUP BY id_payment) AS `igss` ON `igss`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%Parqueo%' OR type LIKE '%Bus%' GROUP BY id_payment) AS `parqueo` ON `parqueo`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%Anticipo%' GROUP BY id_payment) AS `anticipos` ON `anticipos`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Ajuste%' AND amount < 0 GROUP BY id_payment) AS `anticipos_cred` ON `anticipos_cred`.id_payment = payments.idpayments
        LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%ISR%' GROUP BY id_payment) AS `isr` ON `isr`.id_payment = payments.idpayments
        WHERE idperiods = $id_period AND (employees.active = 1 OR employees.termination_date >= periods.end);";

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
    http_response_code(404);
    echo json_encode($sql);
}

?>