<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idperiods = ($request->idperiods);
$start = ($request->start);
$end = ($request->end);
$status = ($request->status);

$return = [];
$i = 0;

    if($start == 'from_close'){
        $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `treasure_hunt`, id_payment FROM credits WHERE type = 'Treasure Hunt' GROUP BY id_payment) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `nearsol_bonus`, id_payment FROM credits WHERE type = 'Bonos Diversos Nearsol TK' GROUP BY id_payment) AS `nearsol_bonus` ON `nearsol_bonus`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `client_bonus`, id_payment FROM credits WHERE type = 'Bonos Diversos Cliente TK' GROUP BY id_payment) AS `client_bonus` ON `client_bonus`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount_hrs,0)) AS `amount_hrs`, SUM(COALESCE(amount_ot,0)) AS `amount_ot`, SUM(COALESCE(amount_holidays,0)) AS `amount_holidays`, id_payment FROM timekeeping_adjustments GROUP BY id_payment) AS `tk_adj`  ON `tk_adj`.id_payment = payments.idpayments WHERE `id_period` = $idperiods;";
    }else{
    if($start != "explicit"){
        $sql = "SELECT * FROM `payments`
                LEFT JOIN periods ON periods.idperiods = payments.id_period
                LEFT JOIN employees ON payments.id_employee = employees.idemployees
                LEFT JOIN hires ON hires.idhires = employees.id_hire
                LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `treasure_hunt`, id_payment 
                    FROM credits 
                    WHERE type = 'Treasure Hunt' GROUP BY id_payment
                ) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `nearsol_bonus`, id_payment FROM credits
                    WHERE type = 'Bonos Diversos Nearsol TK' GROUP BY id_payment
                ) AS `nearsol_bonus` ON `nearsol_bonus`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `client_bonus`, id_payment FROM credits
                    WHERE type = 'Bonos Diversos Cliente TK' GROUP BY id_payment
                ) AS `client_bonus` ON `client_bonus`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount_hrs,0)) AS `amount_hrs`, SUM(COALESCE(amount_ot,0)) AS `amount_ot`,
                    SUM(COALESCE(amount_holidays,0)) AS `amount_holidays`, id_payment FROM timekeeping_adjustments GROUP BY id_payment
                ) AS `tk_adj`  ON `tk_adj`.id_payment = payments.idpayments WHERE `id_employee` = $status;";
    } else if($start == 'explicit'){
        $sql = "SELECT * FROM `payments`
                LEFT JOIN periods ON periods.idperiods = payments.id_period
                LEFT JOIN employees ON payments.id_employee = employees.idemployees
                LEFT JOIN hires ON hires.idhires = employees.id_hire
                LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `treasure_hunt`, id_payment FROM credits 
                    WHERE type = 'Treasure Hunt' GROUP BY id_payment
                ) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `nearsol_bonus`, id_payment FROM credits 
                    WHERE type = 'Bonos Diversos Nearsol TK' GROUP BY id_payment
                ) AS `nearsol_bonus` ON `nearsol_bonus`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `client_bonus`, id_payment FROM credits 
                    WHERE type = 'Bonos Diversos Cliente TK' GROUP BY id_payment
                ) AS `client_bonus` ON `client_bonus`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount_hrs,0)) AS `amount_hrs`, SUM(COALESCE(amount_ot,0)) AS `amount_ot`, SUM(COALESCE(amount_holidays,0)) AS `amount_holidays`,
                    id_payment FROM timekeeping_adjustments GROUP BY id_payment
                ) AS `tk_adj`  ON `tk_adj`.id_payment = payments.idpayments WHERE `id_employee` = $status";
    } else if($start == 'explicit_Termination'){
        $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `treasure_hunt`, id_payment FROM credits WHERE type = 'Treasure Hunt' GROUP BY id_payment) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `nearsol_bonus`, id_payment FROM credits WHERE type = 'Bonos Diversos Nearsol TK' GROUP BY id_payment) AS `nearsol_bonus` ON `nearsol_bonus`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `client_bonus`, id_payment FROM credits WHERE type = 'Bonos Diversos Cliente TK' GROUP BY id_payment) AS `client_bonus` ON `client_bonus`.id_payment = payments.idpayments LEFT JOIN timekeeping_adjustments ON timekeeping_adjustments.id_payment = payments.idpayments WHERE `id_employee` = $status AND periods.end >= employees.HIRING_DATE ORDER BY periods.idperiods DESC LIMIT 0, 6;";
    } else if($start == 'explicit_employee'){
        $sql = "SELECT * FROM `payments` LEFT JOIN periods ON periods.idperiods = payments.id_period LEFT JOIN employees on payments.id_employee = employees.idemployees left join hires on hires.idhires = employees.id_hire left join profiles on profiles.idprofiles = hires.id_profile LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `treasure_hunt`, id_payment FROM credits WHERE type = 'Treasure Hunt' GROUP BY id_payment) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `nearsol_bonus`, id_payment FROM credits WHERE type = 'Bonos Diversos Nearsol TK' GROUP BY id_payment) AS `nearsol_bonus` ON `nearsol_bonus`.id_payment = payments.idpayments LEFT JOIN (SELECT SUM(COALESCE(amount,0)) AS `client_bonus`, id_payment FROM credits WHERE type = 'Bonos Diversos Cliente TK' GROUP BY id_payment) AS `client_bonus` ON `client_bonus`.id_payment = payments.idpayments LEFT JOIN timekeeping_adjustments ON timekeeping_adjustments.id_payment = payments.idpayments WHERE `id_employee` = $status;";
    } else if($start == 'explicit_period'){
        $sql = "SELECT * FROM `payments`
                LEFT JOIN periods ON periods.idperiods = payments.id_period
                LEFT JOIN employees ON payments.id_employee = employees.idemployees
                LEFT JOIN hires ON hires.idhires = employees.id_hire
                LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `treasure_hunt`, id_payment FROM credits 
                    WHERE type = 'Treasure Hunt' GROUP BY id_payment
                ) AS `treasure_hunt` ON `treasure_hunt`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `nearsol_bonus`, id_payment FROM credits 
                    WHERE type = 'Bonos Diversos Nearsol TK' GROUP BY id_payment
                ) AS `nearsol_bonus` ON `nearsol_bonus`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount,0)) AS `client_bonus`, id_payment FROM credits 
                    WHERE type = 'Bonos Diversos Cliente TK' GROUP BY id_payment
                ) AS `client_bonus` ON `client_bonus`.id_payment = payments.idpayments
                LEFT JOIN (
                    SELECT SUM(COALESCE(amount_hrs,0)) AS `amount_hrs`, SUM(COALESCE(amount_ot,0)) AS `amount_ot`, SUM(COALESCE(amount_holidays,0)) AS `amount_holidays`,
                    id_payment FROM timekeeping_adjustments GROUP BY id_payment
                ) AS `tk_adj`  ON `tk_adj`.id_payment = payments.idpayments WHERE `id_employee` = $status AND `payments.id_period` = $end";
}

if($result = mysqli_query($con, $sql)){
    while($res = mysqli_fetch_assoc($result)){
        $return[$i]['idpayments'] = $res['idpayments'];
        $return[$i]['id_employee'] = $res['id_employee'];
        $return[$i]['id_paymentmethod'] = $res['id_paymentmethod'];
        $return[$i]['id_period'] = $res['id_period'];
        $return[$i]['credits'] = $res['credits'];
        $return[$i]['debits'] = $res['debits'];
        $return[$i]['employee_name'] = $res['first_name'] . " " . $res['second_name'] . " " . $res['first_lastname'] . " " . $res['second_lastname'];
        $return[$i]['date'] = $res['date'];
        $return[$i]['start'] = $res['start'];
        $return[$i]['end'] = $res['end'];
        $return[$i]['last_seventh'] = $res['last_seventh'];
        $return[$i]['ot'] = $res['ot'];
        $return[$i]['ot_hours'] = $res['ot_hours'];
        $return[$i]['base_hours'] = $res['base_hours'];
        $return[$i]['productivity_hours'] = $res['productivity_hours'];
        $return[$i]['base'] = $res['base'];
        $return[$i]['productivity'] = $res['productivity'];
        $return[$i]['seventh'] = $res['sevenths'];
        $return[$i]['holidays'] = $res['holidays'];
        $return[$i]['holidays_hours'] = $res['holidays_hours'];
        $return[$i]['base_complete'] = $res['base_complete'];
        $return[$i]['productivity_complete'] = $res['productivity_complete'];
        $return[$i]['total'] = "0";
        $return[$i]['status'] = $res['state'];
        $return[$i]['nearsol_id'] = $res['status'];
        $return[$i]['client_id'] = $res['client_id'];
        $return[$i]['state'] = $res['state'];
        $return[$i]['account'] = $res['id_account'];
        $return[$i]['days'] = "0";
        $return[$i]['id_account_py'] = $res["id_account_py"];
        $return[$i]['nearsol_bonus'] = $res['nearsol_bonus'];
        $return[$i]['client_bonus'] = $res['client_bonus'];
        $return[$i]['treasure_hunt'] = $res['treasure_hunt'];
        $return[$i]['adj_hours'] = $res['amount_hrs'];
        $return[$i]['adj_ot'] = $res['amount_ot'];
        $return[$i]['adj_holidays'] = $res['amount_holidays'];
        $i++;
    }
    echo(json_encode($return));
} else {
    $r = $r[0]['idpayments'] = "ERROR";
    echo(json_encode($r));
}

?>
