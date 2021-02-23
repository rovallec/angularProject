<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
$date = ($request->date);
$id_emp = [];
$hires = [];
$dt = '';
$exp_id = '';
$norm = false;

if(explode(" ", $date)[0] === "<=" || explode(" ", $date)[0] ===  "<"){
	$sql = "SELECT * FROM (SELECT * FROM (SELECT `profiles`.`idprofiles`, `att`.`idattendences`, `hires`.`id_wave`, `employees`.`idemployees`, `hires`.`nearsol_id`, `employees`.`client_id`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `att`.`date`, `att`.`worked_time`, `att`.`scheduled`,`schedules`.`days_off`, `profiles`.`status` FROM `hires` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN `schedules` ON `schedules`.`idschedules` = `hires`.`id_schedule` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires` LEFT JOIN (SELECT * FROM `attendences` WHERE `date` $date) AS `att` ON `att`.`id_employee` = `employees`.`idemployees`) AS `attend` WHERE `idprofiles` = $id ORDER BY `date` DESC LIMIT 15) AS `sub` ORDER BY `date` ASC;";
}else{
	if(explode(" ", $date)[0] ===  "="){
		$sql = "SELECT * FROM (SELECT `profiles`.`idprofiles`, `att`.`idattendences`, `hires`.`id_wave`, `employees`.`idemployees`, `hires`.`nearsol_id`, `employees`.`client_id`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `att`.`date`, `att`.`worked_time`, `att`.`scheduled`,`schedules`.`days_off`, `profiles`.`status` FROM `hires` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN `schedules` ON `schedules`.`idschedules` = `hires`.`id_schedule` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires` LEFT JOIN (SELECT * FROM `attendences` WHERE `date` $date) AS `att` ON `att`.`id_employee` = `employees`.`idemployees`) AS `attend` WHERE `idemployees` = $id ORDER BY `date` ASC;";
	}else{
		if($id == 'NULL'){
			$id_emp = explode(";", $date);
			$dt = $id_emp[0];
			$exp_id = $id_emp[1];
			$sql = "SELECT * FROM `attendences` WHERE `date` = '$dt' AND `id_employee` = '$exp_id'  ORDER BY `date` DESC;";
			$norm = true;
			}else{
				if(strpos($date,"BETWEEN") !== false){
					$sql = "SELECT * FROM (SELECT `profiles`.`idprofiles`, `att`.`idattendences`, `hires`.`id_wave`, `employees`.`idemployees`, `hires`.`nearsol_id`, `employees`.`client_id`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `att`.`date`, `att`.`worked_time`, `att`.`scheduled`,`schedules`.`days_off`, `profiles`.`status` FROM `hires` LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile` LEFT JOIN `schedules` ON `schedules`.`idschedules` = `hires`.`id_schedule` LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires` LEFT JOIN (SELECT * FROM `attendences` WHERE `date` $date) AS `att` ON `att`.`id_employee` = `employees`.`idemployees`) AS `attend` WHERE `idprofiles` = $id ORDER BY `date` ASC";
				}else{
					$sql = "SELECT * FROM (SELECT `profiles`.`idprofiles`, `att`.`idattendences`, `hires`.`id_wave`, `employees`.`idemployees`, `hires`.`nearsol_id`, `employees`.`client_id`, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `att`.`date`, `att`.`worked_time`, `att`.`scheduled`,`schedules`.`days_off`, `profiles`.`status`
				FROM `hires`
				LEFT JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`
				LEFT JOIN `schedules` ON `schedules`.`idschedules` = `hires`.`id_schedule`
				LEFT JOIN `employees` ON `employees`.`id_hire` = `hires`.`idhires`
				LEFT JOIN (SELECT * FROM `attendences` WHERE `date` = '$date') AS `att` ON `att`.`id_employee` = `employees`.`idemployees`) AS `attend` `attend` WHERE `id_wave` = $id  ORDER BY `date` ASC";
				}
			}
		}
	}

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		if(!$norm){
			$hires[$i]['idattendences'] = $row['idattendences'];
			$hires[$i]['id_employee'] = $row['idemployees'];
			$hires[$i]['nearsol_id'] = $row['nearsol_id'];
			$hires[$i]['client_id'] = $row['client_id'];
			$hires[$i]['first_name'] = $row['first_name'];
			$hires[$i]['second_name'] = $row['second_name'];
			$hires[$i]['first_lastname'] = $row['first_lastname'];
			$hires[$i]['second_lastname'] = $row['second_lastname'];
			$hires[$i]['date'] = $row['date'];
			$hires[$i]['worked_time'] = $row['worked_time'];
			$hires[$i]['scheduled'] = $row['scheduled'];
			$hires[$i]['day_off1'] = explode(",", $row['days_off'])[0];
			$hires[$i]['day_off2'] = explode(",", $row['days_off'])[1];
			$hires[$i]['status'] = $row['status'];
			$hires[$i]['id_wave'] = $row['id_wave'];
		}else{
			$hires[$i]['idattendences'] = $row['idattendences'];
			$hires[$i]['id_employee'] = $row['id_employee'];
			$hires[$i]['date'] = $row['date'];
			$hires[$i]['worked_time'] = $row['worked_time'];
			$hires[$i]['scheduled'] = $row['scheduled'];
		}

		$i++;
	}
	
	echo json_encode($hires);
}else{
	http_response_code(404);
	echo($sql);
}
?>