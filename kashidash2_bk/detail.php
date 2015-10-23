<?php
/******************************
 画面名：詳細画面表示
 作成日：2014.03.21
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/
// デバッグ定義
$debug = true;
$VC_post_echo     = false;
$VC_get_echo      = false;
$VC_session_echo  = false;
 if($debug){
	$ING_device_id = 'WI-001';
}


// 共通インクルード処理
require_once('phpinc/config.php');


// 利用詳細用インクルード
require_once('phpinc/detail_func.php');


// HTML head 読み込み
$title = $device[$ING_device_id]['name'] .' | KASHIDASH 弐号機';
$addCss = '<link rel="stylesheet" type="text/css" href="/kashidash2/common/css/print.css" media="print">'."\n";
$addJs = '';
$active = 0;
include_once('template/head.php');

?>
<body>
<?php 
include_once('template/header.php');
?>

<div id="contents">
	<section id="main">
		<h2 class="title01">
			<span class="print_d">各利用詳細画面</span><span class="type01"><span class="print_d">（機種名：</span><?php echo $device[$ING_device_id]['name']; ?><span class="print_d">）</span> </span><span class="type01_p">機種資産番号：<?php if(isset($ING_device_id)) echo $ING_device_id; ?></span>
			<form style="float: right; margin-right: 20px;" name="select_date" method="post" action="/kashidash2/detail.php?device_id=<?php echo $ING_device_id; ?>">
				<select name="year">
					<option value="">指定しない</option>
					<?php
					
						foreach($year as $val){
							$selected = '';
							if($val==$INS_year){
								$selected = 'selected';
							}
							echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
						}
					?>
				</select>
				<select name="month">
					<option value="">指定しない</option>
					<?php
						foreach($month as $val){
							$selected = '';
							if($val==$INS_month){
								$selected = 'selected';
							}
							echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
						}
					?>
				</select>
				<input type="submit" value="　検索する　" class="btn btn-warning btn-xs">
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

			while($table = $stmt->fetch(PDO::FETCH_ASSOC)){
			?>
			<tr>
				<td><?php print(htmlspecialchars($table['l_date'])); ?></td>
				<td><?php print(htmlspecialchars($table['l_time'])); ?></td>
				<td class="nowrap"><?php print(htmlspecialchars($table['l_user'])); ?></td>
				<td class="txleft"><?php print(htmlspecialchars($table['l_purpose'])); ?></td>


				<?php if($authCheck && ($table['l_approval']==null || $table['l_approval']=='')){ ?>
					<td class="boundary">
						<form name="l_approval" action="/kashidash2/detail.php?device_id=<?php echo $ING_device_id ;?>&auth=admin" method="post">
							<input type="text" name="l_auth_name" style="width:3em">
							<input type="hidden" name="id" value="<?php echo $table['id']; ?>">
							<input type="submit" value="　承 認　" class="btn btn-primary btn-xs fs09">
						</form>
					</td>
				<?php }else{ ?>
					<td class="boundary nowrap"><?php print(htmlspecialchars($table['l_approval'])); ?></td>
				<?php } ?>



				<td><?php print(htmlspecialchars($table['r_date'])); ?></td>
				<td><?php print(htmlspecialchars($table['r_time'])); ?></td>
				<td class="nowrap"><?php print(htmlspecialchars($table['r_user'])); ?></td>


				<?php if($authCheck && ($table['r_approval']==null || $table['r_approval']=='')){ ?>
					<td>
						<form name="r_approval" action="/kashidash2/detail.php?device_id=<?php echo $ING_device_id ;?>&auth=admin" method="post">
							<input type="text" name="r_auth_name" style="width:3em">
							<input type="hidden" name="id" value="<?php echo $table['id']; ?>">
							<input type="submit" value="　承 認　" class="btn btn-primary btn-xs fs09">
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


<?php include_once('template/footer.php') ?>

<script src="/kashidash2/common/js/table_pagination.js"></script>
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


</body>
</html>
