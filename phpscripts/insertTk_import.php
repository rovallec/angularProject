<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database_ph.php';

$id_import = 0;
$path = '';
$date = date("Y-m-d");

$res[] = [];

$sql1 = "SELECT idtk_imports FROM `tk_imports` ORDER BY idtk_imports DESC LIMIT 1;";
if($result = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result)){
        $id_import = $row['idtk_imports'];
    }
    $path = "import_". ($id_import + 1) . ".xls";
    $sql = "INSERT INTO `tk_imports` (`idtk_imports`, `date`, `path`) VALUES (null, $date, '$path');";
    if(mysqli_query($con,$sql)){
        $res['path'] = $path;
        $res['idtk_import'] = (mysqli_insert_id($con));
        echo(json_encode($res));
    }
}
?>