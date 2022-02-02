<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_check = ($request->idchecks);


//$id_check = $request->id_check;
//$id_check = $_GET['idchecks'];

$res = [];
$i = 0;

$sql = "SELECT 
          c.idchecks,
          c.place,
          DATE_FORMAT(c.`date`,'%d-%m-%Y') AS date,
          c.value,
          c.name,
          c.description,
          c.negotiable,
          c.nearsol_id,
          c.client_id,
          c.id_account,
          c.document,
          c.bankAccount,
          c.printDetail,
          c.user_create,
          c.creation_date,
          c.payment
        FROM checks c
        WHERE c.idchecks = $id_check;";

try{
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
    $negociable = $res[0]['negotiable'];
    $fecha = $res[0]['place'] . ', ' . $res[0]['date'];
    $nombre = $res[0]['name'];
    $valor = $res[0]['value'];
    $moneda = 'Q';
    $autorizacion = '';
    $concepto = $res[0]['description'];
    $documento = $res[0]['document'];
    $path = '"C:\Users\Public\Cheques.exe"';

    $params = ' -NEGOCIABLE:"' .$negociable . '" -FECHA:"' . $fecha .'" -NOMBRE:"' . $nombre . '" -VALOR:"' .$valor. '" -MONEDA:"' . $moneda . '" -AUTORIZACION:"' . $autorizacion . '" -CONCEPTO:"' . $concepto . '" -DOCUMENTO:"' . $documento . '"';
    $command = $path . $params;
    $ultima_linea = system($command, $return_var);
    echo(json_encode($command));
    http_response_code(200);
    //echo(json_encode("Impresión exitosa"));
  } else {
    echo(json_encode(mysqli_error($con). "<br>" . $sql));
    http_response_code(404);
  } // END ELSE
} catch(\Throwable $e) {
  $error = "Error: |Unable to print the check due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";  
  echo(json_encode($error));
}



?>