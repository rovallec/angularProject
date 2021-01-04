<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_period = ($request->idperiods);

$isr = [];

$sql = "SELECT * FROM `isr` 
        INNER JOIN `employees` ON `employees`.`idemployees` = `isr`.`id_employee`
        INNER JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire`
        INNER JOIN `profiles` ON `profiles`.`idprofiles` = `hires`.`id_profile`
        WHERE `id_period` = $id_period;";

if($result = mysqli_query($con, $sql)){
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $isr[$i]['idemployees'] = $row['idemployees'];
        $isr[$i]['idisr'] = $row['idisr'];
        $isr[$i]['nearsol_id'] = $row['nearsol_id'];
        $isr[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $isr[$i]['gross_income'] = $row['gross_income'];
        $isr[$i]['taxable_income'] = $row['taxable_income'];
        $isr[$i]['anual_tax'] = $row['anual_tax'];
        $isr[$i]['other_retentions'] = $row['other_retentions'];
        $isr[$i]['accumulated'] = $row['accumulated'];
        $isr[$i]['expected'] = $row['expected'];
        $isr[$i]['amount'] = $row['amount'];
        $i++;
    };
    echo json_encode($isr);
}else{
    http_response_code(404);
}

?>
