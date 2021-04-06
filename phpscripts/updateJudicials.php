<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idjudicials = ($request->idjudicials);
$current = ($request->current);

$sql = "UPDATE `judicials` SET `current` = $current WHERE `idjudicials` = $idjudicials";

if(mysqli_query($con,$sql)){
    echo (json_encode("1|"));
} else {
    $r = json_encode("0|" . mysqli_error($con));
    echo ($r);
}
?>