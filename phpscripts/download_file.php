<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$patDoc = ($request->doc_path);


$filepath = "uploads/" . $patDoc;
       header('Content-Description: File Transfer');
       header('Content-Type: application/octet-stream');
       header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
       header('Expires: 0');
       header('Cache-Control: must-revalidate');
       header('Pragma: public');
       header('Content-Length: ' . filesize($filepath));
       flush(); // Flush system output buffer
       readfile($filepath);
    exit;
?>