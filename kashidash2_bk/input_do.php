<?php
/******************************
 画面名：機種登録処理
 作成日：2014.03.21
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/
//header('Location: index.php') ;
require_once('phpinc/config.php');

$debug = (isset($INP_debug) && $INP_debug) ? true : false;

if($debug){
echo '<meta charset="UTF-8">';
echo '<h1>デバッグ中</h1>';
}

// 多重送信防止
// ポストされたワンタイムチケットを取得する。
$ticket = isset($_POST['ticket'])    ? $_POST['ticket']    : '';
// セッション変数に保存されたワンタイムチケットを取得する。
$save   = isset($_SESSION['ticket']) ? $_SESSION['ticket'] : '';
if($ticket === ''){
	die('<p style="margin:30px auto"><h3 style="text-align:center;">データが不正のため登録できませんでした。<br>もう一度貸出申請を行ってください。</h3></p><p style="text-align:center;padding-top:100px;"><a href="#" rel="close" onclick="window.location.reload();">× 閉じる</a></p>');
}
if ($ticket === $save) {
	
	// 日付
	$l_date_year = sprintf('%04d' , $INP_lendingYear);
	$l_date_month = sprintf('%02d' , $INP_lendingMonth);
	$l_date_day = sprintf('%02d' , $INP_lendingDay);
	$l_date = $l_date_year . '-' . $l_date_month . '-' . $l_date_day;
	
	// 時刻
	$l_time_hour = sprintf('%02d' , $INP_lendingHour);
	$l_time_min = sprintf('%02d' , $INP_lendingMinute);
	$l_time = $l_time_hour . ':' . $l_time_min . ':00';
	
	//利用者
	$l_user = $INP_lendingName;
	
	//目的
	$l_purpose = $INP_lendingPurpose;
	
	//データのチェック
	if($l_user == '' || $l_purpose == ''){
		die('<p style="margin:30px auto"><h3 style="text-align:center;">データが不正のため登録できませんでした。<br>もう一度貸出申請を行ってください。</h3></p><p style="text-align:center;padding-top:100px;"><a href="#" rel="close" onclick="window.location.reload();">× 閉じる</a></p>');
	}
	
	//データをDBへ登録
	$device_id = htmlspecialchars($INP_device_id);
	$item = htmlspecialchars($INP_item);
	$l_date = htmlspecialchars($l_date);
	$l_time = htmlspecialchars($l_time);
	$l_user = htmlspecialchars($l_user);
	$l_purpose = htmlspecialchars($l_purpose);
	
	$sql = 'INSERT INTO verifier SET device_id=:device_id, item=:item, l_date=:l_date, l_time=:l_time, l_user=:l_user, l_purpose=:l_purpose';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':device_id',$device_id);
	$stmt->bindParam(':item',$item);
	$stmt->bindParam(':l_date',$l_date);
	$stmt->bindParam(':l_time',$l_time);
	$stmt->bindParam(':l_user',$l_user);
	$stmt->bindParam(':l_purpose',$l_purpose);
	$stmt->execute();
	
	// 貸し出し内容をメールで送信
	mb_language('japanese');
	mb_internal_encoding('UTF8');
	
	if($l_user){
		$to = 'wmd@ejworks.com';
		$subject = '【KASHIDASH】'. $item .'貸出中';
		$body = "[KASHIDASH弐号機]からのお知らせ\r\n\r\n".$l_date_year."年".$l_date_month."月". $l_date_day ."日より\r\n". $l_user ."さんが". $item ."を借りています。\r\n\r\n「". $l_purpose ."」\r\n\r\n確認はこちらから → http://wts14.ejworks.com/kashidash2/";
		$from = mb_encode_mimeheader(mb_convert_encoding('KASHIDASH','JIS','UTF8')).'<kashidash@ejworks.com>';
		$success = mb_send_mail($to,$subject,$body,'From:'.$from);
	}
	
	//echo('item=' . $item . ', l_date=' . $l_date . ', l_time=' . $l_time . ', l_user= ' . $l_user . ', l_purpose= ' . $l_purpose);
	
	#Tips
	#mysqlから連想配列取得
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
} else {
	// マルチポスト
}
unset($_SESSION['ticket']);
?>


<p style="margin:30px auto"><h3 style="text-align:center;">申請完了</h3></p>
<p style="text-align:center;padding-top:100px;"><a href="#" rel="close" onclick="window.location.reload();">× 閉じる</a></p>