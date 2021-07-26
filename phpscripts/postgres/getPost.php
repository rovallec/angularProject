<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
//require '../funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$location = $request->location;
$origin = $request->origin;

if (trim($origin) == '') {
  $where = "where 1 = 1 ";
} else {
  $where =  " where location = '" . $location . "' " .
            " and origin = '" . $origin . "'";
}

$order = " order by date_post DESC;";

$i = 0;
$return = array();
$sql = "select * from posts " . $where . $order;


if($result = pg_query($con,$sql)){
  while($row = pg_fetch_array($result)) {    
    $return[$i]['id'] = $row['id'];
    $return[$i]['location'] = $row['location'];
    $return[$i]['header'] = $row['header'];
    $return[$i]['byline'] = $row['byline'];
    $return[$i]['multimedia'] = $row['multimedia'];
    $return[$i]['fragment'] = $row['fragment'];
    $return[$i]['article'] = $row['article'];
    $return[$i]['author'] = $row['author'];
    $return[$i]['date_post'] = $row['date_post'];
    $return[$i]['origin'] = $row['origin'];
    $return[$i]['label'] = $row['label'];
    $i++;    
  }
//  echo($sql);
    echo(json_encode($return));
}else{
  http_response_code(400);
  echo($con->error);
  echo("<br>");
  echo($sql);
}
?>