<?php
require_once('device.php');
/*
#機種資産番号
$itemArray = array(
					'IS03',
					'iPhone4',
					'iPhone4s',
					'iPhone5',
					'SO01B',
					'GALAXY',
					'GALAXY_NEXUS',
					'INFOBAR',
					'docomo',
					'softbank',
					'au',
					'ArrowsTab',
					'iPad',
					'iPad2',
					'Nexus7_2012',
					'iPad_mini',
					'XPERIA_SO-02E',
					'AQUOS_PHONE_SH-02E',
					'htc_HTL21',
					'iPad_Retina',
					'ICONIA_W510',
					'kindle_fire_HD_89',
					'Nexus10',
					'Xperia_Tablet',
					'Nexus7_2013',
					'Surface_Pro2',
					'iPad_Air',
					'iPad_mini_Retina',
					'iPhone5s',
					'CT-iPad_mini',
					'CT-Nexsus7',
					'CT-GALAXY_NEXUS_Win8',
					'SLP-iPad',
					'SLP-iPad_mini',
					'SLP-iPad_mini_Retina',
					'SLP-iPad_Air',
					'SLP-iPhone4S',
					'SLP-iPhone5',
					'SLP-nexus7_2012',
					'SLP-nexus7_2013',
					'SLP-XPERIA_Tablet_Z',
					'SLP-Galaxy_Tab_10.1',
					'IXY_600',
					'Panasonic_NV-GS250',
					'Velbon'
					);
$numberArray = array(
					'WI-08',
					'WI-01',
					'WI-12',
					'WI-14',
					'WI-02',
					'WI-07',
					'WI-13',
					'WI-09',
					'WI-04',
					'WI-05',
					'WI-06',
					'WI-10',
					'WI-03',
					'WI-11',
					'WI-15',
					'WI-16',
					'WI-17',
					'WI-18',
					'WI-19',
					'WI-20',
					'WI-21',
					'WI-22',
					'WI-23',
					'WI-24',
					'WI-25',
					'WI-26',
					'WI-27',
					'WI-28',
					'WI-29',
					'CT-01',
					'CT-02',
					'CT-03',
					'SLP-01',
					'SLP-02',
					'SLP-03',
					'SLP-04',
					'SLP-11',
					'SLP-12',
					'SLP-21',
					'SLP-22',
					'SLP-31',
					'SLP-42',
					'WI-101',
					'WI-102',
					'WI-103'
					);
foreach($itemArray as $key=>$val){
	$model_number[$val] = $numberArray[$key];
}
*/

#db接続
$url = "localhost";
$user = "wts14";
$pass = "W1uT33Up";
$db = "wts14";
$mysqli = new mysqli($url, $user, $pass, $db) or die(mysql_error());
mysqli_set_charset($mysqli, "utf8");

#指定された機種のデータをDBから取得
$itemName = sprintf('%s', $_GET['item']);
#セレクトタグ値取得
$sql = 'SELECT DISTINCT DATE_FORMAT(l_date,"%Y") as year FROM verifier WHERE item="'.$itemName.'" ORDER BY DATE_FORMAT(l_date,"%Y") ASC';
$year = get_mysql_data($sql,$mysqli);
$sql = 'SELECT DISTINCT DATE_FORMAT(l_date,"%m") as month FROM verifier WHERE item="'.$itemName.'" ORDER BY DATE_FORMAT(l_date,"%m") ASC';
$month = get_mysql_data($sql,$mysqli);
$date = date("Y/m");
$date = explode("/",$date);


#承認機能
if(isset($_GET['auth'])){
	if($_GET['auth']=='admin'){
		$auth = 1;
	}
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(!empty($_POST['l_auth_name'])){
		$sql = 'UPDATE verifier SET l_approval = "'.$_POST['l_auth_name'].'" WHERE id = '.$_POST['id'].'';
		$mysqli->query($sql) or exit('update error');
		$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" AND DATE_FORMAT(l_date,"%Y")="'.$date[0].'" ORDER BY id DESC';
	}else if(!empty($_POST['r_auth_name'])){
		$sql = 'UPDATE verifier SET r_approval = "'.$_POST['r_auth_name'].'" WHERE id = '.$_POST['id'].'';
		$mysqli->query($sql) or exit('update error');
		$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" AND DATE_FORMAT(l_date,"%Y")="'.$date[0].'" ORDER BY id DESC';
	}else if(!empty($_POST['year']) && !empty($_POST['month'])){
		$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" AND DATE_FORMAT(l_date,"%Y")="'.$_POST['year'].'" AND DATE_FORMAT(l_date,"%m")="'.$_POST['month'].'" ORDER BY id DESC';
		$date[0] = $_POST['year'];
		$date[1] = $_POST['month'];
	}else if(!empty($_POST['year']) && empty($_POST['month'])){
		$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" AND DATE_FORMAT(l_date,"%Y")="'.$_POST['year'].'" ORDER BY id DESC';
		$date[0] = $_POST['year'];
		$date[1] = '';
	}else if(empty($_POST['year']) && !empty($_POST['month'])){
		$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" AND DATE_FORMAT(l_date,"%m")="'.$_POST['month'].'" ORDER BY id DESC';
		$date[0] = '';
		$date[1] = $_POST['month'];
	}else{
		$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" ORDER BY id DESC';
	}
}else{
	$sql = 'SELECT * FROM verifier WHERE item="'. $itemName .'" AND DATE_FORMAT(l_date,"%Y")="'.$date[0].'" ORDER BY id DESC';
}


$recordAll = $mysqli->query($sql) or exit("prepare errorn");


$mysqli->close();


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

?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

<title>[機種名]｜KASHIDASH</title>
<link rel="stylesheet" href="/kashidash/common/css/html5reset-1.6.1.css">
<!--link href="css/normalize.css"-->
<link rel="stylesheet" href="/kashidash/common/css/style.css">
<link rel="stylesheet" type="text/css" href="/kashidash/common/css/print.css" media="print">
<script src="/kashidash/common/lib/jquery-1.8.2.min.js"></script>
<script src="/kashidash/common/js/common.js"></script>
<script src="/kashidash/common/js/table_pagination.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var options = {
		currPage : 1, //最初に表示するページ
		//optionsForRows : [1,5,12], //表示する行数
		rowsPerPage : 20 //デフォルト表示行数
	}
	$('#utilizationList').tablePagination(options);
});
</script>
</head>

<body>
<div id="bgStar"></div>
<div id="wrap">
<header>
	<div id="header_in">
		<h1 id="logo"><a href="/kashidash/">KASHIDASH</a></h1>
		<a href="about.html" id="btn_about"><span>貸しダッシュとは？</span></a>
		<a href="index.php" id="btn_pageback">戻る</a>
	</div>
</header>

<div id="contents">
	<section id="main">
		<h2 class="title01">
			<span class="print_d">各利用詳細画面</span><span class="type01"><span class="print_d">（機種名：</span><?php echo $itemName; ?><span class="print_d">）</span> </span><span class="type01_p">機種資産番号：<?php echo $model_number[$itemName]; ?></span>
			<form style="float: right; margin-right: 20px;" name="select_date" method="post" action="/kashidash/detail.php?item=<?php echo $itemName; ?>">
				<select name="year">
					<option value="">指定しない</option>
					<?php
						foreach($year as $val){
							$selected = '';
							if($val['year']==$date[0]){
								$selected = 'selected';
							}
							echo '<option value="'.$val['year'].'" '.$selected.'>'.$val['year'].'</option>';
						}
					?>
				</select>
				<select name="month">
					<option value="">指定しない</option>
					<?php
						foreach($month as $val){
							$selected = '';
							if($val['month']==$date[1]){
								$selected = 'selected';
							}
							echo '<option value="'.$val['month'].'" '.$selected.'>'.$val['month'].'</option>';
						}
					?>
				</select>
				<input type="submit" value="検索する">
			</form>
		</h2>
		<div style="clear: both;"></div>
		<table class="k_table01 k_table02" id="utilizationList">
			<tr class="cap01">
				<th colspan="5" class="group01"><span>貸出</span></th>
				<th colspan="4" class="group02"><span>返却</span></th>
			</tr>
			<tr class="cap02">
				<th>日付</th>
				<th>時刻</th>
				<th>利用者</th>
				<th>利用目的</th>
				<th class="boundary">承認者</th>
				<th>日付</th>
				<th>時刻</th>
				<th>利用者</th>
				<th>承認者</th>
			</tr>

			<?php
			while($table = $recordAll->fetch_assoc()){
			?>
			<tr>
				<td><?php print(htmlspecialchars($table['l_date'])); ?></td>
				<td><?php print(htmlspecialchars($table['l_time'])); ?></td>
				<td class="nowrap"><?php print(htmlspecialchars($table['l_user'])); ?></td>
				<td class="txleft"><?php print(htmlspecialchars($table['l_purpose'])); ?></td>


				<?php if(isset($auth) && ($table['l_approval']==null || $table['l_approval']=='')){ ?>
					<td>
						<form name="l_approval" action="/kashidash/detail.php?item=<?php echo $itemName ;?>&auth=admin" method="post">
							<input type="text" name="l_auth_name">
							<input type="hidden" name="id" value="<?php echo $table['id']; ?>">
							<input type="submit" value="承認">
						</form>
					</td>
				<?php }else{ ?>
					<td class="boundary nowrap"><?php print(htmlspecialchars($table['l_approval'])); ?></td>
				<?php } ?>



				<td><?php print(htmlspecialchars($table['r_date'])); ?></td>
				<td><?php print(htmlspecialchars($table['r_time'])); ?></td>
				<td class="nowrap"><?php print(htmlspecialchars($table['r_user'])); ?></td>


				<?php if(isset($auth) && ($table['r_approval']==null || $table['r_approval']=='')){ ?>
					<td>
						<form name="r_approval" action="/kashidash/detail.php?item=<?php echo $itemName ;?>&auth=admin" method="post">
							<input type="text" name="r_auth_name">
							<input type="hidden" name="id" value="<?php echo $table['id']; ?>">
							<input type="submit" value="承認">
						</form>
					</td>
				<?php }else{ ?>
					<td class="nowrap"><?php print(htmlspecialchars($table['r_approval'])); ?></td>
				<?php } ?>


			</tr>
			<?php
			}
			?>
		</table>
	</section>

	<div class="pagetop"><a href="#">▲PAGE TOPへ</a></div>
</div><!-- #contents -->

<footer>
	<p>copyright (C)2012 KASHIDASH all rights reserved.</p>
</footer>
</div>
</body>
</html>
