<?php
/******************************
 画面名：HOME画面用DB処理
 作成日：2014.03.21
 更新日：2014.04.12
 作成者：松村 y.matsumura@ejworks.com
 ******************************/


foreach($device as $key => $value){

	
	
	
	$stmt = $pdo->prepare('SELECT * FROM verifier WHERE device_id=:device_id ORDER BY id DESC LIMIT 0,1');
	$stmt->bindParam(':device_id', $key);
	$stmt->execute();
	
	$tmp = $stmt->fetch(PDO::FETCH_ASSOC);
	if(is_array($tmp)){
		$device[$key] = array_merge($device[$key],$tmp);
	}else{
		$tmp = array('id'=>'','device_id'=>'','item'=>'','l_date'=>'','l_time'=>'','l_user'=>'','l_purpose'=>'','l_approval'=>'','r_date'=>'','r_time'=>'','r_user'=>'','r_approval'=>'','return_flag'=>'');
		$device[$key] = array_merge($device[$key],$tmp);
	};
	
	//${$value."_data"} = $mysqli->query('SELECT * FROM verifier WHERE item="'. $value .'" ORDER BY id DESC LIMIT 0,1') or exit("prepare errorn");
	//${$value} = ${$value."_data"}->fetch_assoc();
	
}

#返却の成功アラート用クエリ

$returnSccess = (isset($_GET['success'])) ? sprintf('%s', $_GET['success']) : null;

//$mysqli->close();

