<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

$postdata = file_get_contents("php://input");

if(isset($postdata)&&!empty($postdata)){
	$request = json_decode($postdata);
	$usr = ($request->username);
	$pss = ($request->password);
	$authUser = [];
}



define('db_host', '172.18.2.45');
define('db_user', $usr);
define('db_password', $pss);
define('db_name','minearsol');

function connect(){
	$connect = mysqli_connect(db_host,db_user,db_password,db_name);
	return $connect;
};

$mysqlc = new mysqli(db_host,db_user,db_password,db_name);

if(!$mysqlc){
	$authUser[0]['iduser'] = 'N/A';
	$authUser[0]['username'] = 'N/A';
	$authUser[0]['department'] = 'N/A';
	$authUser[0]['user_name'] = 'N/A';
	$authUser[0]['valid'] = 'invalid';
	$authUser[0]['id_role'] = 'N/A';
	echo json_encode($authUser);
}else{
	$sql = "SELECT * FROM `users` where username = '{$usr}';";
	if(!$result = $mysqlc->query($sql)){
		$authUser[0]['iduser'] = 'N/A';
		$authUser[0]['username'] = 'N/A';
		$authUser[0]['department'] = 'N/A';
		$authUser[0]['user_name'] = 'N/A';
		$authUser[0]['valid'] = 'invalid';
		$authUser[0]['id_role'] = 'N/A';
		echo json_encode($authUser);
	}else{
		$i = 0;
		while($row = mysqli_fetch_assoc($result)){
			$authUser[$i]['iduser'] = $row['idUser'];
			$authUser[$i]['username'] = $row['username'];
			$authUser[$i]['department'] = $row['department'];
			$authUser[$i]['user_name'] = $row['user_name'];
			$authUser[$i]['valid'] = $row['valid'];
			$authUser[$i]['id_role'] = $row['id_role'];
			$i++;
		};
		echo json_encode($authUser);
	};
}
?>