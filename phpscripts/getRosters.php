<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$i = 0;
$return = [];
$date = date("Y-m-d");

$sql = "SELECT CONCAT(UPPER(profiles.first_name), ' ', UPPER(profiles.second_name), ' ', UPPER(profiles.first_lastname), ' ', UPPER(profiles.second_lastname)) AS `name`, 
        hires.nearsol_id, employees.client_id, b.start AS `mon_start`, b.end AS `mon_end`, c.start AS `tue_start`, c.end AS `tue_end`, d.start AS `wed_start`,
        d.end AS `wed_end`, e.start AS `fri_start`, e.end AS `fri_end`, f.start AS `sat_start`, f.end AS `sat_end`, g.start AS `sun_start`, g.end AS `sun_end` from rosters
        INNER JOIN employees ON employees.idemployees = rosters.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN roster_types a ON a.idroster_types = rosters.id_type
        INNER JOIN roster_times b ON b.idroster_times = a.id_time_mon
        INNER JOIN roster_times c ON c.idroster_times = a.id_time_tue
        INNER JOIN roster_times d ON d.idroster_times = a.id_time_wed
        INNER JOIN roster_times e ON e.idroster_times = a.id_time_thur
        INNER JOIN roster_times f ON f.idroster_times = a.id_time_fri
        INNER JOIN roster_times g ON g.idroster_times = a.id_time_sat
        INNER JOIN roster_times h ON h.idroster_times = a.id_time_sun
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile;";
echo($sql);
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
    $return[$i]['fri_start'] = $res['fri_start'];
    $return[$i]['fri_end'] = $res['fri_end'];
    $return[$i]['sat_start'] = $res['sat_start'];
    $return[$i]['sun_start'] = $res['sun_start'];
    $i++;
  }
  echo(json_encode($return));
  http_response_code(200);
}
?>