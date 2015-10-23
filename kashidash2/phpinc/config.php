<?php
/******************************
 画面名：カシダッシュ用コンフィグ
 作成日：2014．03.21
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/
//セッション
session_start();

/* イニシャライズ
--------------------------------------------------*/
// パスの設定
define('__ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/kashidash2');
define('__PHPINC__', __ROOT__ . '/phpinc');

// Macの改行File処理
ini_set('auto_detect_line_endings', 1);

// 文字コード 変換
$enc_disp = "UTF-8";
$enc_db   = "utf8";

//ライブラリ読み込み
require_once('receive_func.php');

/* auth判定処理
--------------------------------------------------*/
$authCheck = false;
if((isset($ING_auth) && ($ING_auth == 'admin')) || (isset($_SESSION['auth']) && $_SESSION['auth'] == 'admin')){
	$_SESSION['auth'] = 'admin';
	$authCheck = true;
} else {
  $_SESSION['auth'] = null;
  $authCheck = false;
}

/* DB 接続
--------------------------------------------------*/

/*
$url = "localhost";
$user = "wts14";
$pass = "W1uT33Up";
$db = "wts14";
$mysqli = new mysqli($url, $user, $pass, $db) or die(mysql_error());
mysqli_set_charset($mysqli, $enc_db);
*/

/* DP 接続 PDO版
--------------------------------------------------*/

$dbtype = 'mysql';
$dbhost	= "localhost";
$dbname	= "wts103";
$dbuser = "wts103";
$dbpass = "E5nG8RYp";
$dsn	= "$dbtype:host=$dbhost;dbname=$dbname;charset=$enc_db;";
try{
	$pdo = new PDO($dsn,$dbuser,$dbpass,
		array(PDO::ATTR_EMULATE_PREPARES => false)
		);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
	exit('DB接続失敗: '. $e->getMessage());
}


/* デバイスデータ読み込み
--------------------------------------------------*/
require_once(__PHPINC__ . '/device.php');

