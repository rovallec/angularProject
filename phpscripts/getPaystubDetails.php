<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res = [];

$sql = "SELECT * FROM periods 
        INNER JOIN payments ON payments.id_period = periods.idperiods
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN accounts ON accounts.idaccounts = COALESCE(payments.id_account_py, employees.id_account)
        LEFT JOIN paystub_details ON paystub_details.id_payment = payments.idpayments
        WHERE idperiods = 33 AND employees.active = 1 OR employees.termination_date >= periods.end;";

if($result = mysqli_query($con, $sql)){
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idpayments'] = $row['idpayments'];
        $res[$i]['account_name'] = $row['accounts.name'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['name'] = $row['first_name'] . ' ' . $row['second_name'] . ' ' . $row['first_lasntame'] . ' ' . $row['second_lastname'];
        $res[$i]['idpaystub_deatils'] = $row['idpaystub_deatils'];
        $res[$i]['id_payment'] = $row['id_payment'];
        $res[$i]['recipent'] = $row['email'];
        $res[$i]['sender'] = $row['sender'];
        $res[$i]['subject'] = $row['subject'];
        $res[$i]['content'] = $row['content'];
        $res[$i]['result'] = $row['result'];
        $i++;
    };
    echo json_encode($user);
}else{
    http_response_code(404);
    echo json_encode($sql);
}

?>