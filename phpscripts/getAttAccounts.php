<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$start = ($request->start);
$end = ($request->end);


$res = [];
$i = 0;
$sql = "SELECT name, date, id_account, `completed`, coalesce(`add`,0) + coalesce(`parcial`,0) AS `max` FROM (SELECT name, `tmp2`.date, `tmp2`.id_account, `tmp2`.`completed`, `tmp2`.`add`, SUM(`tmp2`.`active_today`) as `parcial` FROM (SELECT `tmp`.date, `tmp`.id_account, `tmp`.`completed`, `tmp`.`add`, `active_today` FROM (
	SELECT date, id_account, `completed`, SUM(`plus`) AS `add` FROM (
		SELECT `uploaded`.date, `uploaded`.id_account, `completed`, `plus` FROM (SELECT COUNT(idemployees) AS `completed`, date, id_account FROM attendences LEFT JOIN employees ON employees.idemployees = attendences.id_employee GROUP BY id_account, date) AS `uploaded`
		LEFT JOIN (SELECT COUNT(distinct idemployees) AS `plus`, date, id_account FROM hr_processes LEFT JOIN employees ON employees.idemployees = hr_processes.id_employee WHERE id_type = 8 GROUP BY date, id_account) AS `terminated` ON `uploaded`.id_account = `terminated`.id_account AND `uploaded`.date <= `terminated`.date) AS `temp` GROUP BY date, id_account
	) AS `tmp`
	LEFT JOIN (
		SELECT COUNT(idemployees) AS `active_today`, id_account, hiring_date FROM employees WHERE active = 1 GROUP BY id_account, hiring_date
	) AS `today` ON `today`.id_account = `tmp`.id_account AND `today`.hiring_date <= `tmp`.date) AS `tmp2` LEFT JOIN accounts ON accounts.idaccounts = `tmp2`.id_account GROUP BY id_account, date) AS `final` WHERE date between '$start' AND '$end';";

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['idaccounts'] = $row['id_account'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['max'] = $row['max'];
        $res[$i]['value'] = $row['completed'];
        $res[$i]['date'] = $row['date'];
        $res[$i]['show'] = '0';
        $i++;
    }
    echo(json_encode($res));
}
?>