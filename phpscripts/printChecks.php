<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id_checks);


$id_check = $request->id_check;
$place = $request->place;
$fecha = $place . ', ' . $request->date;
$valor = $request->value;
$nombre = $request->name;
$description = $request->description;
$negociable = $request->negotiable;
$id_check = $request->checked;
$id_check = $request->nearsol_id;
$id_check = $request->client_id;
$id_check = $request->id_account;
$id_check = $request->document;
$id_check = $request->bankAccount;


$negociable = 'NO NEGOCIABLE';
$fecha = '2021-12-16';
$nombre = 'HANSEL VIELMAN';
$valor = 151821.10;
$moneda = 'Q';
$autorizacion = 'VICTOR ROGEL';
$concepto = 'SE LO MERECE';
$documento = '231564321';
$path = "C:\Users\Public\Cheques.exe";
$params = ' -NEGOCIABLE:" ' .$negociable . '" -FECHA:"'. $fecha .'" -NOMBRE:"'. $nombre .'" -VALOR:"'.$valor.'" -MONEDA:"'. $moneda .'" -AUTORIZACION:"'. $autorizacion .'" -CONCEPTO:"'. $concepto .'" -DOCUMENTO:"'. $documento .'"';
$command = $path . $params;

echo ("<p>Valor: $valor</p>");


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_check = ($request->id_check);


$res = [];
$i = 0;

$sql = "SELECT * FROM checks WHERE idchecks = $id;";

if ($result = mysqli_query($con, $sql)) {
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['idchecks'] = $row['idchecks'];
    $res[$i]['place'] = $row['place'];
    $res[$i]['date'] = $row['date'];
    $res[$i]['value'] = $row['value'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['description'] = $row['description'];
    $res[$i]['negotiable'] = $row['negotiable'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['client_id'] = $row['client_id'];
    $res[$i]['id_account'] = $row['id_account'];
    $res[$i]['document'] = $row['document'];
    $res[$i]['bankAccount'] = $row['bankAccount'];
    $res[$i]['printDetail'] = $row['printDetail'];
    $res[$i]['user_create'] = $row['user_create'];
    $res[$i]['creation_date'] = $row['creation_date'];
    $i++;
  };
  echo json_encode($res);
} else {
  echo(json_encode(mysqli_error($con). "<br>" . $sql));
  http_response_code(404);
}


try{
  $ultima_linea = system($command, $return_var);
  echo("Impresión exitosa");
} catch(\Throwable $e) {
  $error = "Error: |Unable to print the check due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";  
  echo(json_encode($error));
}



?>