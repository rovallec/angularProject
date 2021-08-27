<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$proccesses = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = $request->idroster_types;
$mon = $request->id_time_mon;
$tue = $request->id_time_tue;
$wed = $request->id_time_wed;
$thur = $request->id_time_thur;
$fri = $request->id_time_fri;
$sat = $request->id_time_sat;
$sun = $request->id_time_sun;

$sql = "UPDATE `roster_types` SET id_time_mon = $mon, id_time_tue = $tue, id_time_wed = $wed, id_time_thur = $thur, id_time_fri = $fir, id_time_sat = $sat, id_time_sun = $sun;";

if(mysqli_query($con, $sql)){
    $sql2 = "SELECT roster_types.*, a.start AS `mon_start`, a.end AS `mon_end`, b.start AS `tue_start`, b.end AS `tue_end`, c.start AS `wed_start`, c.end AS `wed_end`,
        d.start AS `thur_start`, d.end AS `thur_end`, e.start AS `fri_start`, e.end AS `fri_end`, f.start AS `sat_start`, f.end AS `sat_end`,
        g.start AS `sun_start`, g.end AS `sun_end` FROM roster_types
        INNER JOIN roster_times a ON a.idroster_times = roster_types.id_time_mon
        INNER JOIN roster_times b ON b.idroster_times = roster_types.id_time_tue
        INNER JOIN roster_times c ON c.idroster_times = roster_types.id_time_wed
        INNER JOIN roster_times d ON d.idroster_times = roster_types.id_time_thur
        INNER JOIN roster_times e ON e.idroster_times = roster_types.id_time_fri
        INNER JOIN roster_times f ON f.idroster_times = roster_types.id_time_sat
        INNER JOIN roster_times g ON g.idroster_times = roster_types.id_time_sun
        WHERE $str;"
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            $proccesses[$i]['idroster_types'] = $row['idroster_types'];
            $proccesses[$i]['tag'] = $row['tag'];
            $proccesses[$i]['name'] = $row['name'];
            $proccesses[$i]['id_time_mon'] = $row['id_time_mon'];
            $proccesses[$i]['id_time_tue'] = $row['id_time_tue'];
            $proccesses[$i]['id_time_wed'] = $row['id_time_wed'];
            $proccesses[$i]['id_time_thur'] = $row['id_time_thur'];
            $proccesses[$i]['id_time_fri'] = $row['id_time_fri'];
            $proccesses[$i]['id_time_sat'] = $row['id_time_sat'];
            $proccesses[$i]['id_time_sun'] = $row['id_time_sun'];
            $proccesses[$i]['mon_start'] = $row['mon_start'];
            $proccesses[$i]['mon_end'] = $row['mon_end'];
            $proccesses[$i]['tue_start'] = $row['tue_start'];
            $proccesses[$i]['tue_end'] = $row['tue_end'];
            $proccesses[$i]['wed_start'] = $row['wed_start'];
            $proccesses[$i]['wed_end'] = $row['wed_end'];
            $proccesses[$i]['thur_start'] = $row['thur_start'];
            $proccesses[$i]['thur_end'] = $row['thur_end'];
            $proccesses[$i]['fri_start'] = $row['fri_start'];
            $proccesses[$i]['fri_end'] = $row['fri_end'];
            $proccesses[$i]['sat_start'] = $row['sat_start'];
            $proccesses[$i]['sat_end'] = $row['sat_end'];
            $proccesses[$i]['sun_start'] = $row['sun_start'];
            $proccesses[$i]['sun_end'] = $row['sun_end'];
            $i++;
        };
        echo json_encode($proccesses);
    }else{
        http_response_code(400);
    }
}else{
    echo(mysqli_error($con));
}

?>
