<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
    require 'database.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = ($request->idprocesses);
    $sel_proc = ($request->process_name);
    $prof = ($request->id_profile);

    
$i = 0;

$process = [];

    $sql1 = "SELECT `processes`.*, `users`.*, `process_details`.`name` AS `procName`, `process_details`.`value` FROM `processes` LEFT JOIN `process_details` ON `process_details`.`id_process` = `processes`.`idprocesses` LEFT JOIN `users` ON `processes`.`id_user` = `users`.`idUser` where idprocesses = '{$id}'";
    if($res = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($res)){
        $process[0]['idprocesses']=$row['idprocesses'];
        $process[0]['id_role']=$row['id_role'];
        $process[0]['id_profile']=$row['id_profile'];
        $process[0]['processName']=$row['name'];
        $process[0]['description']=$row['description'];
        $process[0]['prc_date']=$row['prc_date'];
        $process[0]['user']=$row['user_name'];
        $process[0]['status']=$row['status'];
        if($row['procName']=='Notes'){
            $process[0]['notes'] = $row['value'];
        }else{
            if($row['procName']=='Result'){
                $process[0]['result'] = $row['value'];
            }else{
                if($row['procName']=='Method'){
                    $process[0]['method'] = $row['value'];
                }
            }
        }
        $i++;
    };
    echo json_encode($process);
}
?>