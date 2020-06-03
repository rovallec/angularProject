<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$dpi = ($request->dpi);

$coincidences = [];
$sql = "SELECT * FROM profiles where `dpi` = '$dpi';";

$i = 0;

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
        $coincidences[$i]['id'] = $row['idprofiles'];
        $coincidences[$i]['name'] = $row['first_name'] . ' ' . $row['second_name'] . ' ' . $row['first_lastname'] . ' ' . $row['second_lastname'];
        $coincidences[$i]['status'] = $row['status'];
        $i++;
    }
	
	echo json_encode($coincidences);
}else{
	http_response_code(404);
}

?>