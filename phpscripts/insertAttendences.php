<?php
    require 'database.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');

    $postdata = file_get_contents("php://input");

    $att = [];
    $id = "";
    $date = "";
    $d_off = "";

    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);
        for ($i=0; $i < count($request); $i++) {
            $sql = $sql . "INSERT INTO `attendences`(`idattendences`, `id_employee`, `date`, `scheduled`, `worked_time`) VALUES ";
            $en = json_encode($request[$i]);
            $de = json_decode($en);
            $id_employee = ($de->id_employee);
            $date = ($de->date);
            $scheduled = ($de->scheduled);
            $worked = ($de->worked_time);
            $id = ($de->id_wave);
            $d_off = ($de->day_off1);
            $sql = $sql . "(NULL, '$id_employee', '$date', '$scheduled', '$worked');";
        }
        if(mysqli_query($con, $sql)){
            if($d_off == "CORRECT"){
                $sql2 = "SELECT * FROM (SELECT `att`.`idattendences`, `hires`.`id_wave`, `employees`.`idemployees`, `hires`.`nearsol_id`, `employees`.`client_id`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `att`.`date`, `att`.`worked_time`, `att`.`scheduled`,`schedules`.`days_off`, `profiles`.`status`
                FROM `hires`
                    LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`
                    LEFT JOIN `schedules` ON `schedules`.`idschedules` = `hires`.`id_schedule`
                    LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires`
                    LEFT JOIN (SELECT * FROM `attendences` WHERE `date` = '$date') AS `att` ON `att`.`id_employee` = `employees`.`idemployees`) AS `attend`";
            }else{
                $sql2 = "SELECT * FROM (SELECT `att`.`idattendences`, `hires`.`id_wave`, `employees`.`idemployees`, `hires`.`nearsol_id`, `employees`.`client_id`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `att`.`date`, `att`.`worked_time`, `att`.`scheduled`,`schedules`.`days_off`, `profiles`.`status`
            FROM `hires`
                LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`
                LEFT JOIN `schedules` ON `schedules`.`idschedules` = `hires`.`id_schedule`
                LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires`
                LEFT JOIN (SELECT * FROM `attendences` WHERE `date` = '$date') AS `att` ON `att`.`id_employee` = `employees`.`idemployees`) AS `attend` WHERE `id_wave` = $id";
            }
                if($result = mysqli_query($con, $sql2))
                {
                    $i = 0;
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $att[$i]['idattendences'] = $row['idattendences'];
                        $att[$i]['id_employee'] = $row['idemployees'];
                        $att[$i]['nearsol_id'] = $row['nearsol_id'];
                        $att[$i]['client_id'] = $row['client_id'];
                        $att[$i]['first_name'] = $row['first_name'];
                        $att[$i]['second_name'] = $row['second_name'];
                        $att[$i]['first_lastname'] = $row['first_lastname'];
                        $att[$i]['second_lastname'] = $row['second_lastname'];
                        $att[$i]['date'] = $row['date'];
                        $att[$i]['worked_time'] = $row['worked_time'];
                        $att[$i]['scheduled'] = $row['scheduled'];
                        $att[$i]['day_off1'] = explode(",", $row['days_off'])[0];
                        $att[$i]['day_off2'] = explode(",", $row['days_off'])[1];
                        $att[$i]['status'] = $row['status'];
                        $i++;
                    }
                    
                    echo json_encode($att);
                }else{
                    http_response_code(404);
                }
        }
    }
?>