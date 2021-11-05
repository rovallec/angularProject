<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$archivo_binario = ($request->file);
$name = validarDatos($request->name);
$tipo = validarDatos($request->type);
$id = validarDatos($request->id);

$sql = "SELECT idfiles FROM file WHERE id_process = $id;";

if($res = mysqli_query($con, $sql)){
  $r = mysqli_fetch_assoc($res);
  if(!is_null($r)) {
    $idfiles = $r['idfiles'];
    $sql1 = "UPDATE file SET 
            archivo = '$archivo_binario'
            WHERE id_process = $id 
            AND idfiles = $idfiles;";
    if(mysqli_query($con, $sql1)){
      echo(json_encode("Uplodated id: " . $idfiles));
      //echo(json_encode('Actualizar');
    } else {
      $error = "0|" . mysqli_error($con) . ' query: ' . $sql1;
      echo(json_encode($error));
      http_response_code(421);
    }    
  } else {
    $sql2 = "INSERT INTO file (idfiles, id_process, name, tipo, archivo) VALUES (NULL, $id, '$name', '$tipo', '$archivo_binario');";  

    if(mysqli_query($con, $sql2)){
      $idfile = mysqli_insert_id($con);
      echo(json_encode("Uplodated id: " . $idfile));
      //echo(json_encode('Insertar');
    } else {
      $error = "Query: " . $sql2;
      //$error = "0|" . mysqli_error($con) . ' query: ' . $sql2;
      echo(json_encode($error));
      http_response_code(422);
    }    
  } 
} else {
  $error = "0|" . mysqli_error($con) . ' query: ' . $sql;
  echo(json_encode($error));
  http_response_code(420);
}

?>