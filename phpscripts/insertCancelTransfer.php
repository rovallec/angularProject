<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id);

$sql = "UPDATE employees
        INNER JOIN (SELECT * FROM employees as `emp`
	                INNER JOIN hr_processes ON hr_processes.id_employee = `emp`.idemployees AND hr_processes.id_type = 16
                    ORDER BY hr_processes.idhr_processes DESC LIMIT 1)
                AS `trns` ON `trns`.id_employee = employees.idemployees
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN hr_processes ON hr_processes.idhr_processes = `trns`.idhr_processes
        SET hires.nearsol_id = SUBSTRING(`trns`.notes,LENGTH(SUBSTRING_INDEX(`trns`.notes,'|',2)) - LENGTH(SUBSTRING_INDEX(`trns`.notes,'|',3))+ 1 ),
        employees.id_account = SUBSTRING_INDEX(SUBSTRING(`trns`.notes,LENGTH(SUBSTRING_INDEX(`trns`.notes,'|',1)) - LENGTH(SUBSTRING_INDEX(`trns`.notes,'|',3)) + 1),'|',1),
        hr_processes.id_type = 21
        WHERE employees.idemployees = $id";
if(mysqli_query($con, $sql)){
    http_response_code(200);
}else{
    http_response_code(404);
}
?>