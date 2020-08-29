<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
$profile = $_POST["profile"];
$user = $_POST["user"];
$rnd =  rand(99,99999);

if($_POST['process']=="updateSignature"){
    $actualName = $user  . "_Signatures_" . ".jpeg";
    $targetPath = "uploads/" . basename($actualName);

    $res = [];

    if(move_uploaded_file($_FILES["file1"]["tmp_name"], $targetPath)){
         $res['EnglishTest'] = $actualName;
         echo json_encode($res);
    }
}

if($_POST['process']=="secondInterview"){

    $names = [];
    $nameNow = [];
    $storeName = [];
    $res;
    $i = 0;

    $names[0] = 'SecondInterview';
    $names[1] = 'JobbOfferLetter';
    $names[2] = 'CommitmentLetter';

    try {
        $nameNow[0] = $user . "_" . $profile . $names[0] . "_" . rand(99,9999) . ".jpeg";
        $storeName[0] = "uploads/" . basename($nameNow[0]);
       if(!isset($_FILES['file1']['error']) || is_array($_FILES['file1']['error'])){
            $nameNow[0] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file1']['tmp_name'], $storeName[0])){
           sha1($_FILES['file1']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }
     
    try {
        $nameNow[1] = $user . "_" . $profile . $names[1] . "_" . rand(99,9999) . ".jpeg";
        $storeName[1] = "uploads/" . basename($nameNow[1]);
       if(!isset($_FILES['file2']['error']) || is_array($_FILES['file2']['error'])){
            $nameNow[1] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file2']['tmp_name'], $storeName[1])){
           sha1($_FILES['file2']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[2] = $user . "_" . $profile . $names[2] . "_" . rand(99,9999) . ".jpeg";
        $storeName[2] = "uploads/" . basename($nameNow[2]);
       if(!isset($_FILES['file3']['error']) || is_array($_FILES['file3']['error'])){
            $nameNow[2] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile');
       }
       if(!move_uploaded_file($_FILES['file3']['tmp_name'], $storeName[2])){
           sha1($_FILES['file3']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    $res['EnglishTest'] = $nameNow[0];
    $res['TypingTest'] = $nameNow[1];
    $res['PsicometricTest'] = $nameNow[2];

    echo(json_encode($res));
}

if($_POST['process']=="drugTest"){
    $names = [];
    $nameNow = [];
    $storeName = [];
    $res;
    $i = 0;

    $names[0] = 'DrugTest';

    try {
        $nameNow[0] = $user . "_" . $profile . $names[0] . "_" . rand(99,9999) . ".jpeg";
        $storeName[0] = "uploads/" . basename($nameNow[0]);
       if(!isset($_FILES['file1']['error']) || is_array($_FILES['file1']['error'])){
            $nameNow[0] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file1']['tmp_name'], $storeName[0])){
           sha1($_FILES['file1']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }

    $res['EnglishTest'] = $nameNow[0];

    echo(json_encode($res));

};

if($_POST['process']=="legalDocumentation"){
    $actualName = $user . "_" . $profile . "_InfonetAuthorization_" . $rnd . ".jpeg";
    $targetPath = "uploads/" . basename($actualName);

    $res = [];

    if(move_uploaded_file($_FILES["file1"]["tmp_name"], $targetPath)){
         $res['EnglishTest'] = $actualName;
         echo json_encode($res);
    }
}

if($_POST['process']=="backgroundCheck"){

    
    $names = [];
    $nameNow = [];
    $storeName = [];
    $res;
    $i = 0;

    $names[0] = 'PoliceRecordsCheck';
    $names[1] = 'CriminalRecordsCheck';
    $names[2] = 'Infonet';
    $names[3] = 'InfonetAuthorization';
    $names[4] = 'BackgroundCheck';
    $names[5] = 'ReferencesCheck';

    try {
        $nameNow[0] = $user . "_" . $profile . $names[0] . "_" . rand(99,9999) . ".jpeg";
        $storeName[0] = "uploads/" . basename($nameNow[0]);
       if(!isset($_FILES['file1']['error']) || is_array($_FILES['file1']['error'])){
            $nameNow[0] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file1']['tmp_name'], $storeName[0])){
           sha1($_FILES['file1']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }
     
    try {
        $nameNow[1] = $user . "_" . $profile . $names[1] . "_" . rand(99,9999) . ".jpeg";
        $storeName[1] = "uploads/" . basename($nameNow[1]);
       if(!isset($_FILES['file2']['error']) || is_array($_FILES['file2']['error'])){
            $nameNow[1] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file2']['tmp_name'], $storeName[1])){
           sha1($_FILES['file2']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[2] = $user . "_" . $profile . $names[2] . "_" . rand(99,9999) . ".jpeg";
        $storeName[2] = "uploads/" . basename($nameNow[2]);
       if(!isset($_FILES['file3']['error']) || is_array($_FILES['file3']['error'])){
            $nameNow[2] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile');
       }
       if(!move_uploaded_file($_FILES['file3']['tmp_name'], $storeName[2])){
           sha1($_FILES['file3']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[3] = $user . "_" . $profile . $names[3] . "_" . rand(99,9999) . ".jpeg";
        $storeName[3] = "uploads/" . basename($nameNow[3]);
       if(!isset($_FILES['file4']['error']) || is_array($_FILES['file4']['error'])){
            $nameNow[3] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file4']['tmp_name'], $storeName[3])){
           sha1($_FILES['file4']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[4] = $user . "_" . $profile . $names[4] . "_" . rand(99,9999) . ".jpeg";
        $storeName[4] = "uploads/" . basename($nameNow[4]);
       if(!isset($_FILES['file5']['error']) || is_array($_FILES['file5']['error'])){
            $nameNow[4] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file5']['tmp_name'], $storeName[4])){
           sha1($_FILES['file5']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[5] = $user . "_" . $profile . $names[5] . "_" . rand(99,9999) . ".jpeg";
        $storeName[5] = "uploads/" . basename($nameNow[5]);
       if(!isset($_FILES['file6']['error']) || is_array($_FILES['file6']['error'])){
            $nameNow[5] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file6']['tmp_name'], $storeName[5])){
           sha1($_FILES['file6']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    $res['EnglishTest'] = $nameNow[0];
    $res['TypingTest'] = $nameNow[1];
    $res['PsicometricTest'] = $nameNow[2];
    $res['PoliceRecrods'] = $nameNow[3];
    $res['CriminalRecords'] = $nameNow[4];
    $res['UtilityBill'] = $nameNow[5];

    echo(json_encode($res));

}

if($_POST['process']=="statalDocumentation"){

    $names = [];
    $nameNow = [];
    $storeName = [];
    $res;
    $i = 0;

    $names[0] = 'DPI';
    $names[1] = 'NIT';
    $names[2] = 'PliceRecords';
    $names[3] = 'CriminalRecords';
    $names[4] = 'UtilityBill';
    $names[5] = 'CityTax';
    $names[6] = 'IGSS';
    $names[7] = 'IRTRA';
    $names[8] = 'HighSchoolDiploma';
    $names[9] = 'References';
    $names[10] = 'InfonetAuthorization';

    try {
        $nameNow[0] = $user . "_" . $profile . $names[0] . "_" . rand(99,9999) . ".jpeg";
        $storeName[0] = "uploads/" . basename($nameNow[0]);
       if(!isset($_FILES['file1']['error']) || is_array($_FILES['file1']['error'])){
            $nameNow[0] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file1']['tmp_name'], $storeName[0])){
           sha1($_FILES['file1']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }
     
    try {
        $nameNow[1] = $user . "_" . $profile . $names[1] . "_" . rand(99,9999) . ".jpeg";
        $storeName[1] = "uploads/" . basename($nameNow[1]);
       if(!isset($_FILES['file2']['error']) || is_array($_FILES['file2']['error'])){
            $nameNow[1] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file2']['tmp_name'], $storeName[1])){
           sha1($_FILES['file2']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[2] = $user . "_" . $profile . $names[2] . "_" . rand(99,9999) . ".jpeg";
        $storeName[2] = "uploads/" . basename($nameNow[2]);
       if(!isset($_FILES['file3']['error']) || is_array($_FILES['file3']['error'])){
            $nameNow[2] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile');
       }
       if(!move_uploaded_file($_FILES['file3']['tmp_name'], $storeName[2])){
           sha1($_FILES['file3']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[3] = $user . "_" . $profile . $names[3] . "_" . rand(99,9999) . ".jpeg";
        $storeName[3] = "uploads/" . basename($nameNow[3]);
       if(!isset($_FILES['file4']['error']) || is_array($_FILES['file4']['error'])){
            $nameNow[3] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file4']['tmp_name'], $storeName[3])){
           sha1($_FILES['file4']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[4] = $user . "_" . $profile . $names[4] . "_" . rand(99,9999) . ".jpeg";
        $storeName[4] = "uploads/" . basename($nameNow[4]);
       if(!isset($_FILES['file5']['error']) || is_array($_FILES['file5']['error'])){
            $nameNow[4] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file5']['tmp_name'], $storeName[4])){
           sha1($_FILES['file5']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[5] = $user . "_" . $profile . $names[5] . "_" . rand(99,9999) . ".jpeg";
        $storeName[5] = "uploads/" . basename($nameNow[5]);
       if(!isset($_FILES['file6']['error']) || is_array($_FILES['file6']['error'])){
            $nameNow[5] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file6']['tmp_name'], $storeName[5])){
           sha1($_FILES['file6']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[6] = $user . "_" . $profile . $names[6] . "_" . rand(99,9999) . ".jpeg";
        $storeName[6] = "uploads/" . basename($nameNow[6]);
       if(!isset($_FILES['file7']['error']) || is_array($_FILES['file7']['error'])){
            $nameNow[6] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file7']['tmp_name'], $storeName[6])){
           sha1($_FILES['file7']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[7] = $user . "_" . $profile . $names[7] . "_" . rand(99,9999) . ".jpeg";
        $storeName[7] = "uploads/" . basename($nameNow[7]);
       if(!isset($_FILES['file8']['error']) || is_array($_FILES['file8']['error'])){
            $nameNow[7] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file8']['tmp_name'], $storeName[7])){
           sha1($_FILES['file8']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[8] = $user . "_" . $profile . $names[8] . "_" . rand(99,9999) . ".jpeg";
        $storeName[8] = "uploads/" . basename($nameNow[8]);
       if(!isset($_FILES['file9']['error']) || is_array($_FILES['file9']['error'])){
            $nameNow[8] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file9']['tmp_name'], $storeName[8])){
           sha1($_FILES['file9']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[9] = $user . "_" . $profile . $names[9] . "_" . rand(99,9999) . ".jpeg";
        $storeName[9] = "uploads/" . basename($nameNow[9]);
       if(!isset($_FILES['file10']['error']) || is_array($_FILES['file10']['error'])){
            $nameNow[9] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
           throw new RuntimeException('0');
       }
       if(!move_uploaded_file($_FILES['file10']['tmp_name'], $storeName[9])){
           sha1($_FILES['file10']['tmp_name'], $ext);
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[10] = $user . "_" . $profile . $names[10] . "_" . rand(99,9999) . ".jpeg";
        $storeName[10] = "uploads/" . basename($nameNow[10]);
       if(!isset($_FILES['file11']['error']) || is_array($_FILES['file11']['error'])){
            $nameNow[10] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file11']['tmp_name'], $storeName[10])){
           sha1($_FILES['file11']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    $res['EnglishTest'] = $nameNow[0];
    $res['TypingTest'] = $nameNow[1];
    $res['PsicometricTest'] = $nameNow[2];
    $res['PoliceRecrods'] = $nameNow[3];
    $res['CriminalRecords'] = $nameNow[4];
    $res['UtilityBill'] = $nameNow[5];
    $res['CityTax'] = $nameNow[6];
    $res['IGSS'] = $nameNow[7];
    $res['doc1'] = $nameNow[8];
    $res['doc2'] = $nameNow[9];
    $res['doc3'] = $nameNow[10];

    echo(json_encode($res));
}


if($_POST['process']=="firstInterview"){

    $names = [];
    $nameNow = [];
    $storeName = [];
    $res;
    $i = 0;

    $names[0] = 'FirstInterview';
    $names[1] = 'Applicaton';
    $names[2] = 'Resume';

    try {
        $nameNow[0] = $user . "_" . $profile . $names[0] . "_" . rand(99,9999) . ".jpeg";
        $storeName[0] = "uploads/" . basename($nameNow[0]);
       if(!isset($_FILES['file1']['error']) || is_array($_FILES['file1']['error'])){
            $nameNow[0] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file1']['tmp_name'], $storeName[0])){
           sha1($_FILES['file1']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }
     
    try {
        $nameNow[1] = $user . "_" . $profile . $names[1] . "_" . rand(99,9999) . ".jpeg";
        $storeName[1] = "uploads/" . basename($nameNow[1]);
       if(!isset($_FILES['file2']['error']) || is_array($_FILES['file2']['error'])){
            $nameNow[1] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file2']['tmp_name'], $storeName[1])){
           sha1($_FILES['file2']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[2] = $user . "_" . $profile . $names[2] . "_" . rand(99,9999) . ".jpeg";
        $storeName[2] = "uploads/" . basename($nameNow[2]);
       if(!isset($_FILES['file3']['error']) || is_array($_FILES['file3']['error'])){
            $nameNow[2] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile');
       }
       if(!move_uploaded_file($_FILES['file3']['tmp_name'], $storeName[2])){
           sha1($_FILES['file3']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    $res['EnglishTest'] = $nameNow[0];
    $res['TypingTest'] = $nameNow[1];
    $res['PsicometricTest'] = $nameNow[2];
    echo(json_encode($res));
}


if($_POST['process']=="testResult"){

    
    $names = [];
    $nameNow = [];
    $storeName = [];
    $res;
    $i = 0;

    $names[0] = 'EnglishTest';
    $names[1] = 'ListeningTest';
    $names[2] = 'TypingTest';
    $names[3] = 'PsicometricTest';

    try {
        $nameNow[0] = $user . "_" . $profile . $names[0] . "_" . rand(99,9999) . ".jpeg";
        $storeName[0] = "uploads/" . basename($nameNow[0]);
       if(!isset($_FILES['file1']['error']) || is_array($_FILES['file1']['error'])){
            $nameNow[0] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file1']['tmp_name'], $storeName[0])){
           sha1($_FILES['file1']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }
     
    try {
        $nameNow[1] = $user . "_" . $profile . $names[1] . "_" . rand(99,9999) . ".jpeg";
        $storeName[1] = "uploads/" . basename($nameNow[1]);
       if(!isset($_FILES['file2']['error']) || is_array($_FILES['file2']['error'])){
            $nameNow[1] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile.jpeg');
       }
       if(!move_uploaded_file($_FILES['file2']['tmp_name'], $storeName[1])){
           sha1($_FILES['file2']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
           
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[2] = $user . "_" . $profile . $names[2] . "_" . rand(99,9999) . ".jpeg";
        $storeName[2] = "uploads/" . basename($nameNow[2]);
       if(!isset($_FILES['file3']['error']) || is_array($_FILES['file3']['error'])){
            $nameNow[2] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile');
       }
       if(!move_uploaded_file($_FILES['file3']['tmp_name'], $storeName[2])){
           sha1($_FILES['file3']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    try {
        $nameNow[3] = $user . "_" . $profile . $names[3] . "_" . rand(99,9999) . ".jpeg";
        $storeName[3] = "uploads/" . basename($nameNow[3]);
       if(!isset($_FILES['file4']['error']) || is_array($_FILES['file4']['error'])){
            $nameNow[3] = 'NoFile.jpeg';
           throw new RuntimeException('uploads/NoFile');
       }
       if(!move_uploaded_file($_FILES['file4']['tmp_name'], $storeName[3])){
           sha1($_FILES['file4']['tmp_name'], $ext);
           throw new RuntimeException('0');
       }else{
       }

    } catch (RuntimeException $th) {
    }

    $res['EnglishTest'] = $nameNow[0];
    $res['TypingTest'] = $nameNow[1];
    $res['PsicometricTest'] = $nameNow[2];
    $res['PoliceRecrods'] = $nameNow[3];

    echo(json_encode($res));
}
?>