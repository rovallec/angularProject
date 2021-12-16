<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
/*
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);
*/

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
$ultima_linea = system($command, $return_var);





echo ("<p>Segundo comando</p>");
//$ultima_linea = exec($command, $return_var);

echo ($ultima_linea);
?>