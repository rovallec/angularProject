<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_employee = ($request->id_employee);
$id_period = ($request->id_period);

$i = 0;
$return = [];
$date = date("Y-m-d");

$sql = "SELECT CONCAT(UPPER(profiles.first_name), ' ', UPPER(profiles.second_name), ' ', UPPER(profiles.first_lastname), ' ', UPPER(profiles.second_lastname)) AS `name`, 
        hires.nearsol_id, employees.client_id, b.start AS `mon_start`, b.end AS `mon_end`, c.start AS `tue_start`, c.end AS `tue_end`, d.start AS `wed_start`,
        d.end AS `wed_end`, e.start AS `thur_start`, e.end AS `thur_end`, f.start AS `fri_start`, f.end AS `fri_end`, g.start AS `sat_start`, g.end AS `sat_end`, rosters.id_type,
        h.start AS `sun_start`, h.end AS `sun_end`, rosters.week_value, COALESCE(`cnt`.`count`,1) AS `count`, idemployees, 
        GROUP_CONCAT(DISTINCT(COALESCE(payments.id_account_py, employees.id_account))) AS `id_account`, idrosters, rosters.id_type, a.tag, a.name AS `roster_name` FROM payments
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        LEFT JOIN rosters ON employees.idemployees = rosters.id_employee
        LEFT JOIN roster_types a ON a.idroster_types = rosters.id_type
        LEFT JOIN roster_times b ON b.idroster_times = a.id_time_mon
        LEFT JOIN roster_times c ON c.idroster_times = a.id_time_tue
        LEFT JOIN roster_times d ON d.idroster_times = a.id_time_wed
        LEFT JOIN roster_times e ON e.idroster_times = a.id_time_thur
        LEFT JOIN roster_times f ON f.idroster_times = a.id_time_fri
        LEFT JOIN roster_times g ON g.idroster_times = a.id_time_sat
        LEFT JOIN roster_times h ON h.idroster_times = a.id_time_sun
        LEFT JOIN (SELECT COUNT(idrosters) AS `count`, id_employee FROM rosters WHERE id_period = $id_period GROUP BY id_employee) AS `cnt` ON `cnt`.id_employee = employees.idemployees
        WHERE idemployees =  $id_employee GROUP BY idrosters;";

if($result = mysqli_query($con, $sql)){
  while($res = mysqli_fetch_assoc($result)){
    $return[$i]['name'] = $res['name'];
    $return[$i]['nearsol_id'] = $res['nearsol_id'];
    $return[$i]['client_id'] = $res['client_id'];
    $return[$i]['mon_start'] = $res['mon_start'];
    $return[$i]['mon_end'] = $res['mon_end'];
    $return[$i]['tue_start'] = $res['tue_start'];
    $return[$i]['tue_end'] = $res['tue_end'];
    $return[$i]['wed_start'] = $res['wed_start'];
    $return[$i]['wed_end'] = $res['wed_end'];
    $return[$i]['thur_start'] = $res['thur_start'];
    $return[$i]['thur_end'] = $res['thur_end'];
    $return[$i]['fri_start'] = $res['fri_start'];
    $return[$i]['fri_end'] = $res['fri_end'];
    $return[$i]['sat_start'] = $res['sat_start'];
    $return[$i]['sat_end'] = $res['sat_end'];
    $return[$i]['sun_start'] = $res['sun_start'];
    $return[$i]['sun_end'] = $res['sun_end'];
    $return[$i]['week_value'] = $res['week_value'];
    $return[$i]['count'] = $res['count'];
    $return[$i]['id_employee'] = $res['idemployees'];
    $return[$i]['id_account'] = $res['id_account'];
    $return[$i]['id_schedule'] = $res['id_type'];
    $return[$i]['idrosters'] = $res['idrosters'];
    $return[$i]['tag'] = $res['tag'];
    $return[$i]['roster_name'] = $res['roster_name'];
    $i++;
  }
  echo(json_encode($return));
  http_response_code(200);
}else{
  echo($sql);
}
?>