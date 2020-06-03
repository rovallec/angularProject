<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$process = [];
$i = 0;

$sql = "SELECT `processes`.*, `users`.`user_name`, `process_details`.`idprocess_details`, `process_details`.name as 'detail_name', `process_details`.`id_process`, `process_details`.`value`, `marketing_details`.*
FROM `processes` 
	LEFT JOIN `users` ON `processes`.`id_user` = `users`.`idUser`
    LEFT JOIN `marketing_details` ON `marketing_details`.`id_process` = `processes`.`idprocesses`
	LEFT JOIN `process_details` ON `process_details`.`id_process` = `processes`.`idprocesses` where `idprocesses` = '{$id}'";

if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $process[$i]['idprocess'] = $row['idprocesses'];
        $process[$i]['id_role'] = $row['id_role'];
        $process[$i]['id_profile'] = $row['id_profile'];
        $process[$i]['name'] =  $row['name'];
        $process[$i]['description'] =  $row['description'];
        $process[$i]['prc_date'] =  $row['prc_date'];
        $process[$i]['status'] =  $row['status'];
        $process[$i]['username'] =  $row['user_name'];
        $process[$i]['source'] = $row['source'];
        $process[$i]['post'] = $row['post'];
        $process[$i]['referrer'] = $row['referrer'];
        $process[$i]['about'] = $row['about'];
        if($row['detail_name']=='Result'){
            $process[$i]['results'] = $row['value'];
        }else{
            if($row['detail_name']=='Notes'){
                $process[$i]['notes'] = $row['value'];
            }else{
                if($row['detail_name']=='English Test'){
                    $process[$i]['english_test'] = $row['value']; 
                    }else{
                        if($row['detail_name']=='Typing Test'){
                            $process[$i]['typing_test'] = $row['value'];
                        }else{
                            if($row['detail_name']=='Psicometric Test'){
                                $process[$i]['psicometric_test'] = $row['value'];
                            }else{
                                if($row['detail_name']=='Listening Test'){
                                    $process[$i]['IGSS'] = $row['value'];
                                }else{
                                    if($row['detail_name']=='Second Interview'){
                                        $process[$i]['english_test'] = $row['value'];
                                    }else{
                                        if($row['detail_name']=='Drug Test'){
                                            $process[$i]['english_test'] = $row['value'];
                                        }else{
                                            if($row['detail_name']=='Commitment Letter'){
                                                $process[$i]['typing_test'] = $row['value'];
                                            }else{
                                                if($row['detail_name']=='NIT'){
                                                    $process[$i]['english_test'] = $row['value'];
                                                }else{
                                                    if($row['detail_name']=='Police Records'){
                                                        $process[$i]['typing_test'] = $row['value'];
                                                    }else{
                                                        if($row['detail_name']=='Criminal Records'){
                                                            $process[$i]['psicometric_test'] = $row['value'];
                                                        }else{
                                                            if($row['detail_name']=='IGSS'){
                                                                $process[$i]['IGSS'] = $row['value'];
                                                            }else{
                                                                if($row['detail_name']=='IRTRA'){
                                                                    $process[$i]['IRTRA'] = $row['value'];
                                                                }else{
                                                                    if($row['detail_name']=='Police Records Check'){
                                                                        $process[$i]['english_test'] = $row['value'];
                                                                    }else{
                                                                        if($row['detail_name']=='Criminal Records Check'){
                                                                            $process[$i]['typing_test'] = $row['value'];
                                                                        }else{
                                                                            if($row['detail_name']=='Infonet'){
                                                                                $process[$i]['psicometric_test'] = $row['value'];
                                                                            }else{
                                                                                if($row['detail_name']=='Background Check'){
                                                                                    $process[$i]['IGSS'] = $row['value'];
                                                                                }else{
                                                                                    if($row['detail_name']=='Infonet Authorization'){
                                                                                        $process[$i]['english_test'] = $row['value'];
                                                                                    }else{
                                                                                        if($row['detail_name']=='References Check'){
                                                                                            $process[$i]['IRTRA'] = $row['value'];
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    echo json_encode($process);
}else{
    http_response_code(404);
}


?>