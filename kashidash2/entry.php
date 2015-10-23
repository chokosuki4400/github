<?php
/******************************
 画面名：貸出エントリー画面
 作成日：2014.04.18
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/
$debug = false;

$ING_device_id = ($debug) ? 'WI-01' : '';
if(!isset($_GET['device_id']) && $debug == false){
	die( 'NO ITEM' );
}

#db接続
// 共通インクルード処理
require_once('phpinc/config.php');

$deviceId = $ING_device_id;

// 多重送信防止
$ticket = md5(uniqid(rand(), true));
$_SESSION['ticket'] = $ticket;

?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="/kashidash2/common/css/bootstrap.min.css">
<link rel="stylesheet" href="/kashidash2/common/css/font-awesome.min.css">
<link rel="stylesheet" href="/kashidash2/common/css/style2.css">
<link rel="stylesheet" href="/kashidash2/common/css/prettyPopin.css">
<div id="popup">
	<section id="main">
		<?php if($debug){echo '<h1>デバッグ中</h1>';} ?>
		<h4 class="title01">貸出画面<span class="type02">「<span class="txColor01">＊</span>」は必須項目</span></h4>
		<form action="input_do.php" method="post" id="lendInput" name="lendInput">
		<div id="entryForm">
			<div class="photo figure" style="float:left;display:block;">
										<?php if(file_exists('img/utilization/'.$device[$deviceId]['img'])){ ?>
<img src="img/utilization/<?php echo $device[$deviceId]['img'] ?>" alt="<?php echo $device[$deviceId]['name'] ?>" width="200" height="auto"  class=""><?php }else{ ?>
<img src="http://dummyimage.com/200x200/999/fff.jpg&text=No+Image" alt="no image" width="200" height="auto" class=""><?php } ?>

				<figcaption>機種名：<?php echo $device[$deviceId]['name'] ?></figcaption>
				<input type="hidden" id="item" name="item" value="<?php echo $device[$deviceId]['name'] ?>">
				<input type="hidden" id="device_id" name="device_id" value="<?php echo $deviceId ?>">
				<input type="hidden" id="ticket" name="ticket" value="<?php echo $ticket?>">
<?php
if($debug){
	echo '<input type="hidden" id="debug" name="debug" value="true">';
}
?>

			</div>
				<div class="entry">
					<div class="entry_in entry01">
						<p>利用者<span class="txColor01">＊</span></p>
						<input type="text" name="lendingName" id="lendingName" class="validate[required]" style="width:10em;" value="">
					</div>
					<div class="entry_in entry02">
						<p>日付<span class="txColor01">＊</span></p>
						<select name="lendingYear" id="lendingYear">
							<?php
								//年の設定
								$startYear = 2012;
								$thisYear = date('Y');
								$y = $startYear;
								$selected = '';
								for($i=0; $i < ($thisYear - $startYear +2); $i++){
									$selected = ($y == $thisYear) ? ' selected="selected"': '';
									echo '<option value="'.$y.'"'.$selected.'>'.$y.'</option>'."\n";
									$y++;
								}
							?>
						</select> /
						<select name="lendingMonth" id="lendingMonth">
							<?php
								//月の設定
								$thisMonth = date('m');
								$selected = '';
								for($i=1; $i <13; $i++){
									$selected = ($i == $thisMonth) ? ' selected="selected"': '';
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";
								}
							?>
						</select> /
						<select name="lendingDay" id="lendingDay">
							<?php
								//日の設定
								$thisDay = date('d');
								$selected = '';
								for($i=1; $i <32; $i++){
									$selected = ($i == $thisDay) ? ' selected="selected"': '';
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";
								}
							?>
						</select>
					</div>
					<div class="entry_in entry03">
						<p>時刻<span class="txColor01">＊</span></p>
						<select name="lendingHour" id="lendingHour">
							<?php
								//時間の設定
								$thisHour = date('H');
								$selected = '';
								for($i=0; $i <24; $i++){
									$selected = ($i == $thisHour) ? ' selected="selected"': '';
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";
								}
							?>
						</select> ：
						<select name="lendingMinute" id="lendingMinute">
							<?php
								//分の設定
								$thisHour = date('i');
								$selected = '';
								$done = false;
								for($i=0; $i <60; $i+=15){
									if(($thisHour < $i + 15) && ($done == false)){
										$selected = ' selected="selected"';
										$done = true;
									} else {
										$selected = '';
									}
									echo '<option value="'.$i.'"'.$selected.'>'.sprintf('%02d',$i).'</option>'."\n";
								}
							?>
						</select>
					</div>
					<div class="entry_in entry04">
						<p>利用目的<span class="txColor01">＊</span></p>
						<textarea name="lendingPurpose" id="lendingPurpose" cols="30" rows="10" class="validate[required]"></textarea>
					</div>
					<div id="js-error" style="display:none;color:#fff;background-color:#c00;font-weight:bold;margin:10px;padding:10px;text-align:center;">入力エラーです。必須項目を入力してください。</div>
				</div><!-- /.entry -->
			</div><!-- /#entryForm -->
			<div id="entryBtn">
				<input type="submit" value="貸出申請をする" class="btn_apply btn btn-sm btn-primary" id="jsSubmit">
			</div><!-- /#entryBtn -->
		</form>
	</section>
</div><!-- #contents -->
<script type="text/javascript">
$(function(){
	$("#jsSubmit").click(function(){
		$(this).attr('disabled','disabled');
		var lendingName = $('#lendingName').val();
		var lendingHour = $('#lendingHour').val();
		var lendingMinute = $('#lendingMinute').val();
		var lendingPurpose = $('#lendingPurpose').val();
		if( (lendingName == '') || (lendingHour == '') || (lendingMinute == '') || (lendingPurpose == '')){
			$('#js-error').css({display:'block'});
			$(this).removeAttr('disabled');
			console.log('lendingName'+lendingName);
			console.log('lendingHour'+lendingHour);
			console.log('lendingMinute'+lendingMinute);
			console.log('lendingPurpose'+lendingPurpose);
			return false;
		} else {
			$('#js-error').css({display:'none'});
			$(this).attr('disabled','disabled');
			$('#lendInput').submit();
		}
	});
})
</script>
