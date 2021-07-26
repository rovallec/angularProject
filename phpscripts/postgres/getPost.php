<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
//require 'funcionesVarias.php';
$location = '';

if(isset($postdata) && !empty($postdata)){
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $location = $request->location;
  $where = " where location = " . $location . ";";
}

$i = 0;
$return = array();
$sql = "select * from posts" . $location;


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
    $i++;    
  }
    echo(json_encode($return));
}else{
  http_response_code(400);
  echo($con->error);
  echo("<br>");
  echo($sql);
}
?>