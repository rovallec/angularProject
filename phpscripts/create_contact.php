<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
if(isset($postdata) && !empty($postdata)) {
  $request = json_decode($postdata);

  //$idcontact_details = ($request->idcontact_details);
  $id_profile = ($request->id_profile);
  $primary_phone = ($request->primary_phone);
  $secondary_phone = ($request->secondary_phone);
  $address = ($request->address);
  $city = ($request->city);
  $email = ($request->email);

  $sql = "INSERT INTO `contact_details`(`id_profile`, `primary_phone`, `secondary_phone`, `address`, `city`, `email`) VALUES ('{$id_profile}', '{$primary_phone}', '{$secondary_phone}', '{$address}', '{$city}', '{$email}');";

  if(mysqli_query($con, $sql))
  {
    $id_contact = mysqli_insert_id($con);
    echo $id_contact;
  }else{
    http_response_code(422);
    echo $sql;
  }
}
?>