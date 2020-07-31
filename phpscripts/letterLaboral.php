<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

echo "<table>";
echo "<tr>";
echo "<td><image src=src='http://168.194.75.13/assets/Nearsol.png' style='height:75px; width:224px></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "</table>";
?>