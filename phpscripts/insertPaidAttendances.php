<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$sql2 = "";
$r = [];
$i = 0;
$sql = "INSERT INTO `minearsol`.`paid_attendances` (`idpaid_attendances`,`id_payroll_value`, `date`, `scheduled`, `worked`, `balance`) VALUES";

for ($i=0; $i < (count($request)); $i++) { 
    if($i != 0){
        $sql = $sql . ",";
    }
    
    //insert:
    $id_payroll_value = $request[$i]->id_payroll_value;
    $date = $request[$i]->date;
    $scheduled = $request[$i]->scheduled;
    $worked = $request[$i]->worked;
    $balance = $request[$i]->balance;
   
    $sql = $sql . 
           "(NULL,$id_payroll_value, '$date', '$scheduled', '$worked', '$balance')";
}
if(mysqli_query($con,$sql)){
    echo("1");
}else{
    $str = $sql2 . "|" . mysqli_error($con);
    echo(json_encode($str));
}
?>