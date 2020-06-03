<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->idprocesses);
$id_profile = ($request->id_profile);

$vew_hire_process = [];

$sql1 = "SELECT * FROM `hires` WHERE `id_profile` = '$id_profile'";

if(mysqli_query($con, $sql1)){
    $sql = "Select `tb_inner4`.*, `usrs`.`user_name` as `reports_to` from (Select `tb_inner3`.*, `wv`.`name` as `wave_name` From (Select `tb_inner2`.*, `hir`.`id_wave` as `wave_id`, `hir`.`nearsol_id` as `nearsol_id`, `hir`.`reports_to` as `reporters_id` FROM (Select `usr`.`user_name` as `created_by`, `tb_inner1`.* from (select `idprocess_details` as `idpd_pd_inner1`, `id_process` as `idp_pd_inner1`,`pd`.`name` as `detail_name`, `value` as `value_name`, `ps`.* FROM `process_details` as `pd` INNER JOIN `processes` as `ps` on `pd`.`id_process` = `ps`.`idprocesses` WHERE `ps`.`name` = 'Campaign Assignation') as `tb_inner1` INNER JOIN `users` as `usr` on `tb_inner1`.`id_user` = `usr`.`idUser`) as `tb_inner2` INNER join `hires` as `hir` on `tb_inner2`.`id_profile` = `hir`.`id_profile`) as `tb_inner3` inner Join `waves` as `wv` on `tb_inner3`.`wave_id` = `wv`.`idwaves`) as `tb_inner4` inner join `users` as `usrs` on `tb_inner4`.`reporters_id` = `usrs`.`idUser` WHERE `idprocesses` = '$id_process';";

if($result = mysqli_query($con, $sql)){
	while($row = mysqli_fetch_assoc($result))
	{
        $vew_hire_process['user'] = $row['created_by'];
        $vew_hire_process['notes'] = $row['value_name'];
        $vew_hire_process['prc_date'] = $row['prc_date'];
        $vew_hire_process['nearsol_id'] = $row['nearsol_id'];
        $vew_hire_process['wave'] = $row['wave_name'];
        $vew_hire_process['reports_to'] = $row['reports_to'];
    }
    echo(json_encode($vew_hire_process));
}
}else{
    $sql2 = "select * from (SELECT `pd`.`name` as `d_name`, `pd`.`value` as `d_value`, `ps`.*, `usr`.`user_name` FROM `process_details` as `pd` inner join `processes` as `ps` on `pd`.`id_process` = `ps`.`idprocesses` inner JOIN `users` as `usr` on `ps`.`id_user` = `usr`.`idUser` WHERE `ps`.`name` = 'Campaign Assignation') as `tb` where `idprocesses` = '$id_process'";
    if($re = mysqli_query($con,$sql2)){
        while($rw = mysqli_fetch_assoc($re)){
            $vew_hire_process['user'] = $rw['user_name'];
            $vew_hire_process['notes'] = $rw['d_value'];
            $vew_hire_process['prc_date'] = $rw['prc_date'];
            $vew_hire_process['nearsol_id'] = 'N/A';
            $vew_hire_process['wave'] = 'N/A';
            $vew_hire_process['reports_to'] = 'N/A';
        }
        echo(json_encode($vew_hire_process));
    }
}

?>