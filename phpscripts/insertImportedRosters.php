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
    
    if($query_res = $transact->query($sql)->fetch_assoc()){
            $id_type = $res[0]['idroster_types'];
        }else{
            $sql_time_mon = "SELECT idroster_times FROM roster_times WHERE (start = '$mon_start' AND end = '$mon_end' AND fixed_schedule = '$mon_fixed') OR ('$mon_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_tue = "SELECT idroster_times FROM roster_times WHERE (start = '$tue_start' AND end = '$tue_end' AND fixed_schedule = '$tue_fixed') OR ('$tue_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_wed = "SELECT idroster_times FROM roster_times WHERE (start = '$wed_start' AND end = '$wed_end' AND fixed_schedule = '$wed_fixed') OR ('$wed_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_thur = "SELECT idroster_times FROM roster_times WHERE (start = '$thur_start' AND end = '$thur_end' AND fixed_schedule = '$thur_fixed') OR ('$thur_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_fri = "SELECT idroster_times FROM roster_times WHERE (start = '$fri_start' AND end = '$fri_end' AND fixed_schedule = '$fri_fixed') OR ('$fri_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_sat = "SELECT idroster_times FROM roster_times WHERE (start = '$sat_start' AND end = '$sat_end' AND fixed_schedule = '$sat_fixed') OR ('$sat_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_sun = "SELECT idroster_times FROM roster_times WHERE (start = '$sun_start' AND end = '$sun_end' AND fixed_schedule = '$sun_fixed') OR ('$sun_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            if($query_mon = $transact->query($sql_time_mon)){
                if($query_tue = $transact->query($sql_time_tue)){
                    if($query_wed = $transact->query($sql_time_wed)){
                        if($query_thur = $transact->query($sql_time_thur)){
                            if($query_fri = $transact->query($sql_time_fri)){
                                if($query_sat = $transact->query($sql_time_sat)){
                                    if($query_sun = $transact->query($sql_time_sun)){
                                        $res_mon = $query_mon->fetch_assoc();
                                        $res_tue = $query_tue->fetch_assoc();
                                        $res_wed = $query_wed->fetch_assoc();
                                        $res_thur = $query_thur->fetch_assoc();
                                        $res_fri = $query_fri->fetch_assoc();
                                        $res_sat = $query_sat->fetch_assoc();
                                        $res_sun = $query_sun->fetch_assoc();
                                        if(count($res_mon) > 0 && count($res_tue) > 0 && count($res_wed) > 0 && count($res_thur) > 0 &&
                                        count($res_fri) > 0 && count($res_sat) > 0 && count($res_sun) > 0){
                                            $id_mon = $res_mon[0]['idroster_times'];
                                            $id_tue = $res_tue[0]['idroster_times'];
                                            $id_wed = $res_wed[0]['idroster_times'];
                                            $id_thur = $res_thur[0]['idroster_times'];
                                            $id_fri = $res_fri[0]['idroster_times'];
                                            $id_sat = $res_sat[0]['idroster_times'];
                                            $id_sun = $res_sun[0]['idroster_times'];
                                            $sql_insert_type = "INSERT INTO roster_types VALUES (NULL, 'IMPORT', NOW(), $id_mon, $id_tue, $id_wed, $id_thur, $id_fri, $id_sat, $id_sun);";
                                            if($query_insert_type = $transact->query($sql_insert_type)){
                                                $id_type = mysqli_insert_id($transact);
                                            }
                                        }else{
                                            if(count($res_mon) <= 0){
                                                $sql_insert_mon = "INSERT INTO roster_times VALUES (NULL, '$mon_start', '$mon_end', '$mon_fixed');";
                                                $id_mon = mysqli_insert_id($transact);
                                            }
                                            if(count($res_tue) <= 0){
                                                $sql_insert_tue = "INSERT INTO roster_times VALUES (NULL, '$tue_start', '$tue_end', '$tue_fixed');";
                                                $id_tue = mysqli_insert_id($transact);
                                            }
                                            if(count($res_wed) <= 0){
                                                $sql_insert_wed = "INSERT INTO roster_times VALUES (NULL, '$wed_start', '$wed_end', '$wed_fixed');";
                                                $id_wed = mysqli_insert_id($transact);
                                            }
                                            if(count($res_thur) <= 0){
                                                $sql_insert_thur = "INSERT INTO roster_times VALUES (NULL, '$thur_start', '$thur_end', '$thur_fixed');";
                                                $id_thur = mysqli_insert_id($transact);
                                            }
                                            if(count($res_fri) <= 0){
                                                $sql_insert_fri = "INSERT INTO roster_times VALUES (NULL, '$fri_start', '$fri_end', '$fri_fixed');";
                                                $id_fri = mysqli_insert_id($transact);
                                            }
                                            if(count($res_sat) <= 0){
                                                $sql_insert_sat = "INSERT INTO roster_times VALUES (NULL, '$sat_start', '$sat_end', '$sat_fixed');";
                                                $id_sat = mysqli_insert_id($transact);
                                            }
                                            if(count($res_sun) <= 0){
                                                $sql_insert_sun = "INSERT INTO roster_times VALUES (NULL, '$sun_start', '$sun_end', '$sun_fixed');";
                                                $id_sun = mysqli_insert_id($transact);
                                            }
                                            
                                            $sql_insert_type = "INSERT INTO roster_types VALUES (NULL, 'IMPORT', NOW(), $id_mon, $id_tue, $id_wed, $id_thur, $id_fri, $id_sat, $id_sun);";
                                            if($query_insert_type = $transact->query($sql_insert_type)){
                                                $id_type = mysqli_insert_id($transact);
                                            }else{
                                                echo($sql_insert_type);
                                            }
                                        }
                                    }else{
                                        echo($sql_time_sun);
                                    }
                                }else{
                                    echo($sql_time_sat);
                                }
                            }else{
                                echo($sql_time_fri);
                            }
                        }else{
                            echo($sql_time_thur);
                        }
                    }else{
                        echo($sql_time_wed);
                    }
                }else{
                    echo($sql_time_tue);
                }
            }else{
                echo($sql_time_mon);
            }
        $sql_insert_roster = "INSERT INTO rosters VALUES (NULL, $id_employee, $id_period, $id_type, '1');";
        if($transact->query($sql_insert_roster)){
            $count++;
        }else{
            echo($sql_insert_roster);
        }
    }
}
if($count >= count($request) - 1){
    $transact->commit();
    echo($count);
}else{
    $transact->rollback();
}
?>