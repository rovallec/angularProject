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

$id_mon = '';
$id_tue = '';
$id_wed = '';
$id_thur = '';
$id_fri = '';
$id_sat = '';
$id_sun = '';

$id_type = '';

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
    if($result = mysqli_query($con, $sql)){
        if(mysqli_num_rows($result)){
            while($row = mysqli_fetch_assoc($result)){
                $id_type = $row['idroster_types'];
            }
        }else{
            $sql_time_mon = "SELECT idroster_times FROM roster_times WHERE (start = '$mon_start' AND end = '$mon_end' AND fixed_schedule = '$mon_fixed') OR ('$mon_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_tue = "SELECT idroster_times FROM roster_times WHERE (start = '$tue_start' AND end = '$tue_end' AND fixed_schedule = '$tue_fixed') OR ('$tue_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_wed = "SELECT idroster_times FROM roster_times WHERE (start = '$wed_start' AND end = '$wed_end' AND fixed_schedule = '$wed_fixed') OR ('$wed_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_thur = "SELECT idroster_times FROM roster_times WHERE (start = '$thur_start' AND end = '$thur_end' AND fixed_schedule = '$thur_fixed') OR ('$thur_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_fri = "SELECT idroster_times FROM roster_times WHERE (start = '$fri_start' AND end = '$fri_end' AND fixed_schedule = '$fri_fixed') OR ('$fri_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_sat = "SELECT idroster_times FROM roster_times WHERE (start = '$sat_start' AND end = '$sat_end' AND fixed_schedule = '$sat_fixed') OR ('$sat_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            $sql_time_sun = "SELECT idroster_times FROM roster_times WHERE (start = '$sun_start' AND end = '$sun_end' AND fixed_schedule = '$sun_fixed') OR ('$sun_start' = 'NULL') ORDER BY idroster_times DESC LIMIT 1;";
            
            if($mon_start != 'NULL'){
                $sql_insert_mon = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$mon_start' AS `2`, '$mon_end' AS `3`, '$mon_fixed' AS `4`) 
                                    AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$mon_start' AND end = '$mon_end' AND fixed_schedule = '$mon_fixed');";
                if($query_mon = $transact->query($sql_insert_mon)){

                }else{
                    echo($sql_insert_mon);
                }
            }
            if($tue_start != 'NULL'){
                $sql_insert_tue = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$tue_start' AS `2`, '$tue_end' AS `3`, '$tue_fixed' AS `4`) 
                               AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$tue_start' AND end = '$tue_end' AND fixed_schedule = '$tue_fixed');";
                if($query_tue = $transact->query($sql_insert_tue)){

                }else{
                    echo($sql_insert_tue);
                }
            }
            if($wed_start != 'NULL'){
                $sql_insert_wed = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$wed_start' AS `2`, '$wed_end' AS `3`, '$wed_fixed' AS `4`) 
                               AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$wed_start' AND end = '$wed_end' AND fixed_schedule = '$wed_fixed');";
                if($query_wed = $transact->query($sql_insert_wed)){

                }else{
                    echo($sql_insert_wed);
                }       
            }
            if($thur_start != 'NULL'){
                $sql_insert_thur = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$thur_start' AS `2`, '$thur_end' AS `3`, '$thur_fixed' AS `4`) 
                                AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$thur_start' AND end = '$thur_end' AND fixed_schedule = '$thur_fixed');";
                if($query_thur = $transact->query($sql_insert_thur)){

                }else{
                    echo($sql_insert_thur);
                }
            }
            if($fri_start != 'NULL'){
                $sql_insert_fri = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$fri_start' AS `2`, '$fri_end' AS `3`, '$fri_fixed' AS `4`) 
                               AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$fri_start' AND end = '$fri_end' AND fixed_schedule = '$fri_fixed');";
                if($query_fri = $transact->query($sql_insert_fri)){

                }else{
                    echo($sql_insert_fri);
                }
            }
            if($sat_start != 'NULL'){
                $sql_insert_sat = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$sat_start' AS `2`, '$sat_end' AS `3`, '$sat_fixed' AS `4`) 
                               AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$sat_start' AND end = '$sat_end' AND fixed_schedule = '$sat_fixed');";
                if($query_sat = $transact->query($sql_insert_sat)){

                }else{
                    echo($sql_insert_sat);
                }
            }
            if($sun_start != 'NULL'){
                $sql_insert_sun = "INSERT INTO roster_times SELECT * FROM (SELECT NULL AS `1`, '$sun_start' AS `2`, '$sun_end' AS `3`, '$sun_fixed' AS `4`) 
                               AS `temp`WHERE NOT EXISTS(SELECT * FROM roster_times WHERE start = '$sun_start' AND end = '$sun_end' AND fixed_schedule = '$sun_fixed');";
                if($query_sun = $transact->query($sql_insert_sun)){

                }else{
                    echo($sql_insert_sun);
                }
            }
            if($res_mon = $transact->query($sql_time_mon)){
                if($res_tue = $transact->query($sql_time_tue)){
                    if($res_wed = $transact->query($sql_time_wed)){
                        if($res_thur = $transact->query($sql_time_thur)){
                            if($res_fri = $transact->query($sql_time_fri)){
                                if($res_sat = $transact->query($sql_time_sat)){
                                    if($res_sun = $transact->query($sql_time_sun)){
                                        while($assoc_mon = mysqli_fetch_assoc($res_mon)){
                                            $id_mon = $assoc_mon['idroster_times'];
                                        }
                                        while($assoc_tue = mysqli_fetch_assoc($res_tue)){
                                            $id_tue = $assoc_tue['idroster_times'];
                                        }
                                        while($assoc_wed = mysqli_fetch_assoc($res_wed)){
                                            $id_wed = $assoc_wed['idroster_times'];
                                        }
                                        while($assoc_thur = mysqli_fetch_assoc($res_thur)){
                                            $id_thur = $assoc_thur['idroster_times'];
                                        }
                                        while($assoc_fri = mysqli_fetch_assoc($res_fri)){
                                            $id_fri = $assoc_fri['idroster_times'];
                                        }
                                        while($assoc_sat = mysqli_fetch_assoc($res_sat)){
                                            $id_sat = $assoc_sat['idroster_times'];
                                        }
                                        while($assoc_sun = mysqli_fetch_assoc($res_sun)){
                                            $id_sun = $assoc_sun['idroster_times'];
                                        }
                                        $sql_insert_type = "INSERT INTO roster_types VALUES (NULL, 'IMPORT', NOW(), $id_mon, $id_tue, $id_wed, $id_thur, $id_fri, $id_sat, $id_sun);";
                                        if($query_insert_type = mysqli_query($transact,$sql_insert_type)){
                                            $id_type = mysqli_insert_id($transact);
                                            $sql_insert_roster = "INSERT INTO rosters VALUES (NULL, $id_employee, $id_period, $id_type, '1');";
                                            if(mysqli_query($transact,$sql_insert_roster)){
                                                $count++;
                                            }else{
                                                echo($sql_insert_roster);
                                            }
                                        }else{
                                            echo($sql_insert_type);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }else{
        echo($sql);
    }
}
if($count >= count($request) - 1){
    $transact->commit();
    echo($count);
}else{
    $transact->rollback();
}
?>