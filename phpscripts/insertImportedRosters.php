<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$count = 0;

$res = [];
$res_mon = [];
$res_tue = [];
$res_wed = [];
$res_thur = [];
$res_fri = [];
$res_sat = [];
$res_sun = [];

$transact->begin_transaction();

for ($i=0; $i < count($request); $i++) {
    $en = json_encode($request[$i]);
    $roster = json_decode($en);
    $mon_start = validarDatos($roster->mon_start);
    $mon_end = validarDatos($roster->mon_end);
    $mon_fixed = validarDatos($roster->mon_fixed);
    $tue_start = validarDatos($roster->tue_start);
    $tue_end = validarDatos($roster->tue_start);
    $tue_fixed = validarDatos($roster->tue_fixed);
    $wed_start = validarDatos($roster->wed_start);
    $wed_end = validarDatos($roster->wed_start);
    $wed_fixed = validarDatos($roster->wed_fixed);
    $thur_start = validarDatos($roster->thur_start);
    $thur_end = validarDatos($roster->thur_start);
    $thur_fixed = validarDatos($roster->thur_fixed);
    $fri_start = validarDatos($roster->fri_start);
    $fri_end = validarDatos($roster->fri_start);
    $fri_fixed = validarDatos($roster->fri_fixed);
    $sat_start = validarDatos($roster->sat_start);
    $sat_end = validarDatos($roster->sat_start);
    $sat_fixed = validarDatos($roster->sat_fixed);
    $sun_start = validarDatos($roster->sun_start);
    $sun_end = validarDatos($roster->sun_start);
    $sun_fixed = validarDatos($roster->sun_fixed);
    $id_employee = validarDatos($roster->id_employee);
    $id_period = validarDatos($roster->id_period);

    $sql = "SELECT idroster_types FROM roster_types
            INNER JOIN roster_times mon ON (mon.idroster_times = roster_types.id_time_mon AND mon.start = '$mon_start' AND mon.end = '$mon_end' AND mon.fixed_schedule = '$mon_fixed') OR ('$mon_start' = 'NULL')
            INNER JOIN roster_times tue ON (tue.idroster_times = roster_types.id_time_tue AND tue.start = '$tue_start' AND tue.end = '$tue_end' AND tue.fixed_schedule = '$tue_fixed') OR ('$tue_start' = 'NULL')
            INNER JOIN roster_times wed ON (wed.idroster_times = roster_types.id_time_wed AND wed.start = '$wed_start' AND wed.end = '$wed_end' AND wed.fixed_schedule = '$wed_fixed') OR ('$wed_start' = 'NULL')
            INNER JOIN roster_times thur ON (thur.idroster_times = roster_types.id_time_thur AND thur.start = '$thur_start' AND thur.end = '$thur_end' AND thur.fixed_schedule = '$thur_fixed') OR ('$thur_start' = 'NULL')
            INNER JOIN roster_times fri ON (fri.idroster_times =  roster_types.id_time_fri AND fri.start = '$fri_start' AND fri.end = '$fri_end' AND fri.fixed_schedule = '$fri_fixed') OR ('$fri_start' = 'NULL')
            INNER JOIN roster_times sat ON (sat.idroster_times =  roster_types.id_time_sat AND sat.start = '$sat_start' AND sat.end = '$sat_end' AND sat.fixed_schedule = '$sat_fixed') OR ('$sat_start' = 'NULL')
            INNER JOIN roster_times sun ON (sun.idroster_times =  roster_types.id_time_sun AND sun.start = '$sun_start' AND sun.end = '$sun_end' AND sun.fixed_schedule = '$sun_fixed') OR ('$sun_start' = 'NULL')
            ORDER BY idroster_types DESC LIMIT 1;";
    
    if($query_res = $transact->query($sql)-fetch_assoc()){
        echo($query_res);
    }
}
if($count >= count($request) - 1){
    $transact->commit();
    echo($count);
}else{
    $transact->rollback();
}
?>