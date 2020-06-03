<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $idprocesses = ($request[0]->idprocesses);
    $id_role = ($request[0]->id_role);
    $id_profile = ($request[0]->id_profile);
    $name = ($request[0]->name);
    $description = ($request[0]->description);
    $prc_date = ($request[0]->prc_date);
    $status = ($request[0]->status);
    $id_user = ($request[0]->id_user);
    $iddocuments = ($request[0]->iddocuments);
    $result = ($request[0]->result);
    $notes = ($request[0]->notes);
    $soruce = ($request[0]->source);
    $post = ($request[0]->post);
    $referrer = ($request[0]->referrer);
    $about = ($request[0]->about);


    if(isset($postdata) && !empty($postdata)){
        if($name=='Test Results'){
            $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
            if(mysqli_query($con, $sql)){
                $idprocesses = mysqli_insert_id($con);
                $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                $sql3 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                    if(mysqli_query($con,$sql2)){
                        if(mysqli_query($con, $sql3)){
                            for ($i=0; $i < 4; $i++){
                                $doc_res = ($request[$i]->doc_res);
                                $doc_type = ($request[$i]->doc_type);
                                $sql5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', '{$doc_type}', '{$doc_res}');";
                                $doc_path = ($request[$i]->doc_path);
                                $sql4 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                if(mysqli_query($con,$sql4)){
                                    if(mysqli_query($con,$sql5)){
                                        echo $idprocesses;
                                    }else{
                                        http_response_code(404);
                                    }
                                }else{
                                    http_response_code(404);
                                };
                            };
                        };
                    };
            }
        }else{
            if($name=='First Interview'){
                $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
                if(mysqli_query($con, $sql)){
                    $idprocesses = mysqli_insert_id($con);
                    $sql3 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                    $sql5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                    $sql7 = "INSERT INTO `marketing_details`(`idmarketing_details`, `id_process`, `source`, `post`, `referrer`, `about`) VALUES (null, '{$idprocesses}', '{$soruce}', '{$post}', '{$referrer}', '{$about}')";
                    if(mysqli_query($con, $sql7)){
                        if(mysqli_query($con,$sql3)){
                            if(mysqli_query($con, $sql5)){
                                for ($i=0; $i < 3; $i++){
                                    $doc_type = ($request[$i]->doc_type);
                                    $doc_path = ($request[$i]->doc_path);
                                    $sql4 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                    if(mysqli_query($con,$sql4)){
                                        echo $idprocesses;
                                    }else{
                                        http_response_code(404);
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                if($name=='Second Interview'){
                    $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
                    if(mysqli_query($con,$sql)){
                        $idprocesses = mysqli_insert_id($con);
                        $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                        $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                        if(mysqli_query($con,$sql1)){
                            if(mysqli_query($con,$sql2)){
                                for ($i=0; $i < 3; $i++){
                                    $doc_type = ($request[$i]->doc_type);
                                    $doc_path = ($request[$i]->doc_path);
                                    $sql4 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                    if($i==0){
                                        $doc_res = ($request[$i]->doc_res);
                                        $slq5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', '{$doc_type}', '{$doc_res}');";;
                                        if(mysqli_query($con,$slq5)){
                                            echo $idprocesses;
                                        }else{
                                            http_response_code(404);
                                        }
                                    }
                                    if(mysqli_query($con,$sql4)){
                                        echo $idprocesses;
                                    }else{
                                        http_response_code(404);
                                    }
                                }
                            }
                        }
                    }
                }else{
                    if($name=='Documentation'){
                        $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
                        if(mysqli_query($con,$sql)){
                            $idprocesses = mysqli_insert_id($con);
                            $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                            $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                            if(mysqli_query($con,$sql1)){
                                if(mysqli_query($con,$sql2)){
                                    for ($i=0; $i < 11; $i++){
                                        $doc_type = ($request[$i]->doc_type);
                                        $doc_path = ($request[$i]->doc_path);
                                        $sql4 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                        if($i==1 || $i==2 || $i==3 || $i==6 || $i==7){
                                            $doc_res = ($request[$i]->doc_res);
                                            $slq5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', '{$doc_type}', '{$doc_res}');";;
                                            if(mysqli_query($con,$slq5)){
                                                echo $idprocesses;
                                            }else{
                                                http_response_code(404);
                                            }
                                        }
                                        if(mysqli_query($con,$sql4)){
                                            echo $idprocesses;
                                        }else{
                                            http_response_code(404);
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        if($name=='Background Check'){
                            $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
                            if(mysqli_query($con,$sql)){
                                $idprocesses = mysqli_insert_id($con);
                                $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                                $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                                if(mysqli_query($con,$sql1)){
                                    if(mysqli_query($con,$sql2)){
                                        for ($i=0; $i < 6; $i++){
                                            $doc_type = ($request[$i]->doc_type);
                                            $doc_path = ($request[$i]->doc_path);
                                            $sql4 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                            if($i != 3){
                                                $doc_res = ($request[$i]->doc_res);
                                                $slq5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', '{$doc_type}', '{$doc_res}');";
                                                if(mysqli_query($con,$slq5)){
                                                    if(mysqli_query($con,$sql4)){
                                                        echo $idprocesses;
                                                    }else{
                                                        http_response_code(404);
                                                    }
                                                }
                                            }else{
                                                if(mysqli_query($con,$sql4)){
                                                    echo $idprocesses;
                                                }else{
                                                    http_response_code(404);
                                                }
                                            }
                                        }
                                        if(mysqli_query($con,$sql4)){
                                            echo $idprocesses;
                                        }else{
                                            http_response_code(404);
                                        }
                                    }
                                }
                            }
                        }else{
                            if($name=='Legal Documentation'){
                                $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
                                if(mysqli_query($con,$sql)){
                                    $idprocesses = mysqli_insert_id($con);
                                    $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                                    $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                                    if(mysqli_query($con,$sql1)){
                                        if(mysqli_query($con,$sql2)){
                                            $doc_type = ($request[0]->doc_type);
                                            $doc_path = ($request[0]->doc_path);
                                            $doc_res = ($request[0]->doc_res);
                                            $sql4 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                            $sql5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', '{$doc_type}', '{$doc_res}');";
                                            if(mysqli_query($con,$sql4)){
                                                if(mysqli_query($con,$sql5)){
                                                    echo $idprocesses;
                                                }else{
                                                    http_response_code(404);
                                                }
                                            }
                                        }
                                    }
                                }
                            }else{
                                if($name=='Drug Test'){
                                    $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', '{$status}');";
                                    if(mysqli_query($con, $sql)){
                                        $idprocesses = mysqli_insert_id($con);
                                        $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Notes', '{$notes}');";
                                        $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', 'Result', '{$result}');";
                                        if(mysqli_query($con,$sql1)){
                                            if(mysqli_query($con,$sql2)){
                                                $doc_type = ($request[0]->doc_type);
                                                $doc_path = ($request[0]->doc_path);
                                                $doc_res = ($request[0]->doc_res);
                                                $sql3 = "INSERT INTO `documents`(`iddocuments`, `id_profile`, `id_process`, `doc_type`, `doc_path`,`active`) VALUES (null, '{$id_profile}', '{$idprocesses}', '{$doc_type}', '{$doc_path}', 1);";
                                                $sql4 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}', '{$doc_type}', '{$doc_res}');";
                                                if(mysqli_query($con,$sql3)){
                                                    if(mysqli_query($con,$sql4)){
                                                        echo $idprocesses;
                                                    }else{
                                                        http_response_code(404);
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

?>