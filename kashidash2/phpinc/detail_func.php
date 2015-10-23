<?php
/******************************
 画面名：詳細画面用DB処理
 作成日：2014.03.21
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/

/*
// GETで機種名があるかどうかの処理
if(isset($ING_item)){
  // 機種情報が合った場合セッションに代入
	$_SESSION['item'] = $itemName = sprintf('%s', $ING_item);
} else if($debug){
	$_SESSION['item'] = $itemName = 'au';// デバッグ時には携帯AUを表示
}
// セッションデータから機種データを取得
if(isset($_SESSION['item'])){
	$itemName = sprintf('%s', $_SESSION['item']);
}
*/

// GETでデバイスIDがあるかどうかの処理
if(isset($ING_device_id)){
  // デバイスIDがあった場合セッションに代入
	$_SESSION['device_id'] = $device_id = sprintf('%s', $ING_device_id);
} else if($debug){
	//$_SESSION['device_id'] = $device_id = sprintf('%s', 'WI-06');// デバッグ時にはデバイスIDを WI-06 (au) に設定

	// GETで機種名があるかどうかの処理
	if(isset($ING_item)){
	  // 機種情報が合った場合セッションに代入
		$_SESSION['item'] = $itemName = sprintf('%s', $ING_item);
	} else if($debug){
		$_SESSION['item'] = $itemName = 'au';// デバッグ時には携帯AUを表示
	}
	// セッションデータから機種データを取得
	if(isset($_SESSION['item'])){
		$itemName = sprintf('%s', $_SESSION['item']);
	}

}
// セッションデータから機種データを取得
if(isset($_SESSION['device_id'])){
	$device_id = sprintf('%s', $_SESSION['device_id']);
}


// 絞込用POSTデータが有る場合の処理
$dataPost = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $dataPost = true;
  $_SESSION['year']  = $INS_year = '';
  $_SESSION['month'] = $INS_month  = '';
	foreach($_POST as $key => $val){
	  $key_name = 'INS_'.$key;
	  if(!is_array($val)){
		${$key_name} = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
	    $_SESSION[$key] = ${$key_name} = stripslashes(${$key_name});
	  } else {
	      $_SESSION[$key] = ${$key_name} = array_map("htmlspecialchars", $val);
	  }
	}
} else {
  // POSTデータがない場合セッションから取得
  foreach($_SESSION as $key => $val){
    $key_name = 'INS_'.$key;
	  if(!is_array($val)){
  		${$key_name} = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
      $_SESSION[$key] = ${$key_name} = stripslashes(${$key_name});
	  } else {
      $_SESSION[$key] = ${$key_name} = array_map("htmlspecialchars", $val);
	  }
	  if($VC_session_echo){
	  	echo $_SESSION[$key].'='.$val."\n";
	  }
  }
}




#セレクトタグ値取得
/*
$sql = 'SELECT DISTINCT DATE_FORMAT(l_date,"%Y") as year FROM verifier WHERE item="'.$itemName.'" ORDER BY DATE_FORMAT(l_date,"%Y") ASC';
$year = get_mysql_data($sql,$mysqli);
$sql = 'SELECT DISTINCT DATE_FORMAT(l_date,"%m") as month FROM verifier WHERE item="'.$itemName.'" ORDER BY DATE_FORMAT(l_date,"%m") ASC';
$month = get_mysql_data($sql,$mysqli);
*/
// sql処理をPDOに書き換え
$sql = 'SELECT DISTINCT DATE_FORMAT(l_date,"%Y") as year FROM verifier WHERE device_id=:device_id ORDER BY DATE_FORMAT(l_date,"%Y") ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':device_id',$device_id);
$stmt->execute();
foreach($stmt as $val){
	$year[] = $val['year'];
}
$sql = 'SELECT DISTINCT DATE_FORMAT(l_date,"%m") as month FROM verifier WHERE device_id=:device_id ORDER BY DATE_FORMAT(l_date,"%m") ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':device_id',$device_id);
$stmt->execute();
foreach($stmt as $val){
	$month[] = $val['month'];
}

$date = date("Y/m");
$date = explode("/",$date);


if($dataPost || ($_SERVER['PHP_SELF'] == '/kashidash2/detailprint.php') || ($_SERVER['PHP_SELF'] == '/kashidash2/detailprint.pdf') ){
	if(!empty($INP_l_auth_name)){
		$sql = 'UPDATE verifier SET l_approval=:l_approval WHERE id =:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':l_approval',$INS_l_auth_name);
		$stmt->bindParam(':id',$INS_id);
		$stmt->execute();

		$sql = 'SELECT * FROM verifier WHERE device_id=:device_id AND DATE_FORMAT(l_date,"%Y")=:l_date ORDER BY id DESC';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':device_id', $device_id);
		$stmt->bindParam(':l_date', $date[0]);
		$stmt->execute();

		$log = '$INS_l_auth_name '.$INS_l_auth_name;
	}else if(!empty($INP_r_auth_name)){
		$sql = 'UPDATE verifier SET r_approval=:r_approval WHERE id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':r_approval',$INS_r_auth_name);
		$stmt->bindParam(':id',$INS_id);
		$stmt->execute();

		$sql = 'SELECT * FROM verifier WHERE device_id=:device_id AND DATE_FORMAT(l_date,"%Y")=:l_date ORDER BY id DESC';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':device_id',$device_id);
		$stmt->bindParam(':l_date',$date[0]);
		$stmt->execute();

		$log = '$INS_r_auth_name'. $INS_r_auth_name;
	}else if(!empty($INS_year) && !empty($INS_month)){
		$sql = 'SELECT * FROM verifier WHERE device_id=:device_id AND DATE_FORMAT(l_date,"%Y")=:l_dateY AND DATE_FORMAT(l_date,"%m")=:l_date ORDER BY id DESC';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':device_id',$device_id);
		$stmt->bindParam(':l_dateY',$INS_year);
		$stmt->bindParam(':l_date',$INS_month);
		$stmt->execute();

		$date[0] = $INS_year;
		$date[1] = $INS_month;
		$log = 'year month';
	}else if(!empty($INS_year) && empty($INS_month)){
		$sql = 'SELECT * FROM verifier WHERE device_id=:device_id AND DATE_FORMAT(l_date,"%Y")=:l_dateY ORDER BY id DESC';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':device_id',$device_id);
		$stmt->bindParam(':l_dateY',$INS_year);
		$stmt->execute();

		$date[0] = $INS_year;
		$date[1] = '';
		$log = 'no-month';
	}else if(empty($INS_year) && !empty($INS_month)){
		$sql = 'SELECT * FROM verifier WHERE device_id=:device_id AND DATE_FORMAT(l_date,"%m")=:l_dateM ORDER BY id DESC';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':device_id',$device_id);
		$stmt->bindParam(':l_dateM',$INS_month);
		$stmt->execute();

		$date[0] = '';
		$date[1] = $INS_month;
		$log = 'no-year';
	}else{
		$sql = 'SELECT * FROM verifier WHERE device_id=:device_id ORDER BY id DESC';
		$log = 'no-date';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':device_id', $device_id);
		$stmt->execute();
	}
}else{
	$sql = 'SELECT * FROM verifier WHERE device_id=:device_id AND DATE_FORMAT(l_date,"%Y")=:l_date ORDER BY id DESC';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':device_id', $device_id);
	$stmt->bindParam(':l_date', $date[0]);
	$stmt->execute();

	$_SESSION['year']  = $INS_year = $date[0];
	$_SESSION['month'] = $INS_month  = '';
	$log = 'not post';
}

$log ='';


function get_mysql_data($query,$mysqli){
	/* 接続状況をチェックします */
	if (mysqli_connect_errno()) {
	    echo mysqli_connect_error();
	    exit();
	}
	/* 連想配列で取得 */
	$dbd = array();
	if($result = $mysqli->query($query)){
		while($row = $result->fetch_assoc()){
			$dbd[] = $row;
		}
		$result->close();
	}else{
		echo $mysqli->error;
		exit();
	}
	return $dbd;
}
