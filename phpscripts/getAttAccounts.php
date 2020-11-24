<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "SELECT name, `attendance`.date, `cnt0`, COALESCE(`cnt`,0) -  COALESCE(`cnt2`,0) FROM (
    SELECT COUNT(idattendences) as `cnt0`, name, date, idaccounts FROM (
    select idaccounts, attendences.idattendences, accounts.name, date from attendences 
    left join employees on employees.idemployees = attendences.id_employee 
    left join hires on hires.idhires = employees.id_hire 
    left join profiles on profiles.idprofiles = hires.id_profile 
    left join accounts on accounts.idaccounts = employees.id_account
    ) as `atteTemp` group by date, idaccounts)  as `attendance` 
    LEFT JOIN (SELECT COUNT(idemployees) as `cnt`, id_account FROM employees where active = 1 GROUP BY id_account) as `tmp` ON `tmp`.id_account = `attendance`.idaccounts
    LEFT JOIN (SELECT COUNT(idemployees) as `cnt2`, id_account, date FROM hr_processes LEFT JOIN employees ON employees.idemployees = hr_processes.id_employee WHERE hr_processes.id_type = 8 group by id_account, date) as `tmp2` ON `tmp2`.id_account = `attendance`.idaccounts AND `tmp2`.date = `attendance`.date;";
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['idaccounts'] = $row['idaccounts'];
        $res[$i]['name'] = $row['name'];
        $i++;
    }
    echo(json_encode($res));
}
?>