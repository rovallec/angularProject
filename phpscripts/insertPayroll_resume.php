<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$sql2 = "";
$r = [];
$i = 0;
$sql = "INSERT INTO `minearsol`.`payroll_resume` (`idpayroll_resume`, `id_employee`, `name`, `id_period`, `nearsol_id`, `client_id`, `account`, `vacations`, `janp`, `jap`, `igss`, `igss_hrs`, `insurance`, `other_hrs`) VALUES";

for ($i=0; $i < (count($request)); $i++) { 
    if($i != 0){
        $sql = $sql . ",";
    }
    
    //insert:
    $id_employee = $request[$i]->id_employee;
    $id_period = $request[$i]->id_period;
    $account = $request[$i]->account;
    $nearsol_id = $request[$i]->nearsol_id;
    $client_id = $request[$i]->client_id;
    $name = $request[$i]->name;
    $vacations = $request[$i]->vacations;
    $janp = $request[$i]->janp;
    $jap = $request[$i]->jap;
    $igss = $request[$i]->igss;
    $igss_hrs = $request[$i]->igss_hrs;
    $insurance = $request[$i]->insurance;
    $other_hrs = $request[$i]->other_hrs;
   
    $sql = $sql . 
           "(NULL, $id_employee, '$name', '$id_period', '$nearsol_id', '$client_id', '$account', '$vacations', '$janp', '$jap', '$igss', '$igss_hrs', '$insurance', '$other_hrs')";
}
if(mysqli_query($con,$sql)){
    echo("1");
}else{
    $str = $sql2 . "|" . mysqli_error($con);
    echo(json_encode($str));
}
?>