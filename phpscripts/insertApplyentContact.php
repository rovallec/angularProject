<?php
    require 'database.php';
    header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

    $postdata = file_get_contents("php://input");
    $prefix = "";

    if(isset($postdata) && !empty($postdata)){

        $request = json_decode($postdata);

        $idprocesses = ($request->idprocesses);
        $id_role = ($request->id_role);
        $id_profile = ($request->id_profile);
        $processName = ($request->processName);
        $description = ($request->description);
        $prc_date   = ($request->prc_date);
        $id_user = ($request->id_user);
        $status = ($request->status);
        $notes = ($request->notes);
        $result = ($request->result);
        $method = ($request->method);

        if($processName=="Candidate Contact"){
            $sql = "INSERT INTO `processes`(`idprocesses`,`id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null,'{$id_role}','{$id_profile}','{$processName}', '{$description}', '{$prc_date}','{$id_user}', '{$status}');";
            if(mysqli_query($con, $sql)){
                $idprocesses = mysqli_insert_id($con);
                $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null,'{$idprocesses}','Notes', '{$notes}')";
                $sql3 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null,'{$idprocesses}','Result', '{$result}')";
                $sql4 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null,'{$idprocesses}','Method', '{$method}')";

                if(mysqli_query($con, $sql2)){
                    if(mysqli_query($con,$sql3)){
                        if(mysqli_query($con,$sql4)){
                            echo mysqli_insert_id($con);
                        };
                    };
                };
            }else{
                http_response_code(422);
            };
        }else{
            if($processName=="Candidate Rejection"){
                $sql = "INSERT INTO `processes`(`idprocesses`,`id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null,'{$id_role}','{$id_profile}','{$processName}', '{$description}', '{$prc_date}','{$id_user}', '{$status}');";
                if(mysqli_query($con, $sql)){
                    $idprocesses = mysqli_insert_id($con);
                    $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null,'{$idprocesses}','Notes', '{$notes}')";
                    $sql11 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null,'{$idprocesses}','Method', '{$method}')";
                    $sql3 = "UPDATE `profiles` SET `status`= '{$method}' WHERE `idprofiles` = '{$id_profile}'";
                    $sql4 = "SELECT * FROM `hires` WHERE `id_profile` = '$id_profile';";
                    if($result = mysqli_query($con,$sql4)){
                        $row = mysqli_fetch_assoc($result);
                        $id_hire = $row['idhires'];
                        $id_wave = $row['id_wave'];
                        $id_schedule = $row['id_schedule'];
                        $sql5 = "SELECT * FROM `hires` WHERE `id_wave` = '$id_wave';";
                        $i = 0;
                        if($result2 = mysqli_query($con, $sql5)){
                            while($row2 = mysqli_fetch_assoc($result2)){
                                $i++;
                            }
                            $i = $i - 1;
                            $sql7 = "UPDATE `waves` SET `hires`= '{$i}' WHERE `idwaves` = '$id_wave';";
                            if(mysqli_query($con,$sql7)){
                                $sql8 = "SELECT * FROM `hires` WHERE `id_schedule` = '$id_schedule';";
                                $i = 0;
                                if($result3 = mysqli_query($con, $sql8)){
                                    while($row3 = mysqli_fetch_assoc($result3)){
                                        $i++;
                                    }
                                    $i = $i - 1;
                                    $sql9 = "UPDATE `schedules` SET `actual_count`= '$i' ,`available`= '1' WHERE `idschedules` = '$id_schedule';";
                                    if(mysqli_query($con, $sql9)){
                                        if(mysqli_query($con, $sql2)){
                                            if(mysqli_query($con,$sql3)){
                                                if(mysqli_query($con,$sql11)){
                                                    $sql6 = "DELETE FROM `hires` WHERE `idhires` = $id_hire;";
                                                    if(mysqli_query($con,$sql6)){
                                                        http_response_code(404);
                                                        $sql_search_prefix = "SELECT * FROM `waves` WHERE `idwaves` = '$id_wave'";
                                                        if($prf_result = (mysqli_query($con,$sql_search_prefix))){
                                                            while($rs = mysqli_fetch_assoc($prf_result)){
                                                                $prefix = $rs['prefix'];
                                                            }
                                                        }

                                                        $count_hire = 0;
                                                        $ns_id = "";
                                                        $sql_hires = "SELECT * FROM `hires` WHERE `id_wave` = '$id_wave';";
                                                        if($hrs_res = mysqli_query($con,$sql_hires)){
                                                           while($rs_hrs = mysqli_fetch_assoc($hrs_res)){
                                                                $count_hire = $count_hire + 1;
                                                                $ns_id = $prefix . str_pad(($count_hire), 2, "0", STR_PAD_LEFT);
                                                                $idhires = $rs_hrs['idhires'];
                                                                $sql_update_hires = "UPDATE `hires` SET `nearsol_id` = '$ns_id' WHERE `idhires` = '$idhires';";
                                                                if(mysqli_query($con,$sql_update_hires)){
                                                                    echo("1");
                                                                }else{
                                                                    echo("0");
                                                                }
                                                            }
                                                        }
                                                            echo mysqli_insert_id($con);
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
                    }else{
                        if(mysqli_query($con, $sql2)){
                            if(mysqli_query($con,$sql3)){
                                echo mysqli_insert_id($con);
                            }
                        }
                    }
                }
            }else{
                if($processName=="New Hire"){
                    $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '$id_role', '$id_profile', '$processName', '$description', '$prc_date', '$id_user', '$status');";
                    if(mysqli_query($con, $sql)){
                        $id = mysqli_insert_id($con);
                        $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '$id', 'Method', '$method');";
                        $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '$id', 'Notes', '$notes');";
                        if(mysqli_query($con,$sql1)){
                            if(mysqli_query($con,$sql2)){
                                $sql3 = "UPDATE `profiles` SET `status`= '$method' WHERE `idprofiles`=$id_profile;";
                                if(mysqli_query($con,$sql3)){
                                    echo("1");
                                }else{
                                    http_response_code(404);
                                }
                            }
                        }
                    }
                }else{
                    if($processName=="Reception Notes"){
                        $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '$id_role', '$id_profile', '$processName', '$description', '$prc_date', '$id_user', '$status');";
                        if(mysqli_query($con, $sql)){
                            $id = mysqli_insert_id($con);
                            $sql1 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '$id', 'Method', '$method');";
                            $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '$id', 'Notes', '$notes');";
                            if(mysqli_query($con, $sql1)){
                                if(mysqli_query($con,$sql2)){
                                    echo("1");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>