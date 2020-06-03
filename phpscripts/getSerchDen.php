<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$sql = "SELECT *  FROM `profiles` where `status` = 'rejected'";

for($i=0; $i < count($request) ; $i++){
    $paramters = ($request[$i]->filter);
    switch ($paramters) {
        case 'ID':
        $paramters = 'idprofiles';
        break;
        case 'First Name':
            $paramters = 'first_name';
        break;
        case 'Second Name':
            $paramters = 'second_name';
        break;
        case 'First Lastname':
            $paramters = 'first_lastname';
        break;
        case 'Second Lastname':
            $paramters = 'second_lastname';
        break;
    }
    $values = ($request[$i]->value);
    $sql = $sql . " and `{$paramters}` = '{$values}'";
}

$profiles = [];


if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$profiles[$i]['idprofiles'] = $row['idprofiles'];
		$profiles[$i]['first_name'] = $row['first_name'];
		$profiles[$i]['second_name'] = $row['second_name'];
		$profiles[$i]['first_lastname'] = $row['first_lastname'];
		$profiles[$i]['second_lastname'] = $row['second_lastname'];
		$profiles[$i]['dpi'] = $row['dpi'];
		$profiles[$i]['status'] = $row['status'];
		$i++;
	}
	
	echo json_encode($profiles);
}else{
	http_response_code(404);
}
?>