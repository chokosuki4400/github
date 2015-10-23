<?php
header('Location: index.php?success=true') ;

#db接続
$url = "localhost";
$user = "wts14";
$pass = "W1uT33Up";
$db = "wts14";
$mysqli = new mysqli($url, $user, $pass, $db) or die(mysql_error());
mysqli_set_charset($mysqli, "utf8");

#返却のデータをDBに登録
$itemName = sprintf('%s', $_GET['item']);
$dataId = sprintf('%d', $_GET['id']);
$today = date('Y-m-d');
$time = date('H:i:s');
$l_user = $mysqli->query('SELECT l_user FROM verifier WHERE id=' . $dataId) or exit("prepare errorn");
$r_user_all = $l_user->fetch_assoc();
$r_user = $r_user_all['l_user'];
$sql = 'UPDATE verifier SET return_flag= 1, r_date="'. $today .'", r_time="'. $time .'", r_user="'. $r_user .'" WHERE id=' . $dataId;
$mysqli->query($sql) or exit("prepare errorn");

?>	