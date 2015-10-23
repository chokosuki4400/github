<?php
/******************************
 画面名：詳細画面表示
 作成日：2014.03.21
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/
// デバッグ定義
$debug = false;
$VC_post_echo     = false;
$VC_get_echo      = false;
$VC_session_echo  = false;

// 共通インクルード処理
require_once('phpinc/config.php');

// 利用詳細用インクルード
require_once('phpinc/index_func.php');

$debug_str = $device;

// HTML head 読み込み
$title = 'KASHIDASH 弐号機';
$addCss = '<link rel="alternate" media="print" href="indexprint.php" type="application/pdf">'."\n";
$active = 0;

include_once('template/head.php');


?>
<body class="index">
<?php

include_once('template/header.php');
?>

<div class="container alpha">
	<div class="row">
	    <div class="col-xs-9 col-sm-10">
		  <aside id="news" class="alert alert-danger">
		  	<ul>
		  		<li class="news-item">借りる端末が「貸出中」や「未処理」の場合は、<em class="label label-danger" style="font-size:1em;padding:3px 10px">必ず管理者に連絡して</em> 貸出処理ができるようにしてもらってください。</li>
		  		<li class="news-item">予約や日をまたいで使用する人は必ず<a href="mailto:wmd@ejworks.com">wmdにメール</a>してください。</li>
		  		<li class="news-item">端末のバージョンを上げた場合は必ず<a href="mailto:wmd@ejworks.com">wmdにメール</a>してください。</li>
		  	</ul>
		  </aside>
		</div>
		<div class="col-xs-3 col-sm-2 tar">
			<button class="print btn btn-default" onclick="javascript:window.open('indexprint.pdf');">　<i class="fa fa-print"></i> 印刷　</button>
		</div>
	</div>
</div>


<div class="container">
	<div class="row">

<?php
foreach($device as $id => $val){
	$id = strtoupper($id);

/*
	echo '<pre>';
	print_r($val);
	echo '</pre>';
*/
?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

<?php
	switch($val['administrator']){
		case 'wi':
			$suffix = 'success';
		break;
		case 'ct':
			$suffix = 'info';
		break;
		case 'slp':
			$suffix = 'info';
		break;
	}
	if((isset($val['r_approval']) && $val['r_approval'] == null) && (isset($val['l_user']) && $val['l_user'] != null)){
		echo "\t\t\t".'<div class="panel panel-default clearfix '.$val['administrator'].'">'."\n";
	}else{
		echo "\t\t\t".'<div class="panel panel-'.$suffix.' clearfix'.$val['administrator'].'">'."\n";
	}

?>
				<div class="media panel-heading">
					<?php
						echo '<span class="label label-'.$suffix.' did">';
						echo $id;
					?></span>
					<a class="pull-left imgThumb" href="detail.php?device_id=<?php echo $id ?>&item=<?php echo $val['name'] ?>">
						<?php if(file_exists('img/utilization/'.$val['img'])){ ?>
<img src="img/utilization/<?php echo $val['img'] ?>" alt="<?php echo $val['name'] ?>" width="80" height="auto" class=""><?php }else{ ?>
<img src="http://dummyimage.com/80x80/999/fff.jpg&text=No+Image" alt="no image" width="80" height="auto" class=""><?php } ?>

					</a>
					<div class="media-body">
						<p class="info">
							<?php
								echo $val['name'];
								if($val['os']){ echo '<br>'.$val['os'].' '.$val['version_major'];}
								if($val['version_minor']){ echo '.'.$val['version_minor'];}
							 ?>
							<?php if($val['misc']){
								echo '<br>'.$val['misc'];
							} ?></p>
					<?php
						if((isset($val['return_flag']) && $val['return_flag'] == 0) && (isset($val['l_user'] ) && $val['l_user'] != null)){
					?>
						<p class="info2">
						<?php echo $val['l_date'] ?>
						<?php echo $val['l_time'] ?><br>
						<em><?php echo $val['l_user'] ?></em>
						<?php
							if(empty($val['l_approval'])){
								echo '<a class="pull-left imgThumb" href="detail.php?device_id='.$id.'&amp;item='. $val['name'] .'">';
								echo '<span class="label label-danger labelAccept"><i class="fa fa-exclamation-triangle"></i> 未承認</span></a>';
							}else{
								echo '<span class="label label-success labelAccept">承認済</span>';
							}
						?>
						</p>
						<div class="btn_box">
								<a href="#" class="btn_return btn btn-danger btn-sm" id="return_<?php echo $val['id'] ?>" data-id="<?php echo $val['id'] ?>" role="button">　<i class="fa fa-unlock"></i>&nbsp;返 却　</a>
						</div>
							<?php
								}else{
							?>
								<?php
									if(empty($val['r_approval']) && !empty($val['l_user'])){
										echo '<a class="pull-left imgThumb" href="detail.php?device_id='.$id.'&amp;item='. $val['name'] .'">';
										echo '<span class="label label-danger labelAccept"><i class="fa fa-exclamation-triangle"></i> 返却承認待ち</span></a>';
									}else{
										echo '<div class="btn_box">';
									  	echo '<div><a href="entry.php?device_id='.$id.'&amp;item=' . $val['name']. '" class="btn_lend btn btn-'.$suffix.' btn-sm" role="button" rel="prettyPopin">　<i class="fa fa-lock"></i>&nbsp;貸 出　</a></div></div>';
									}
								?>
							<?php
							}
							?>
					</div><!-- ./media-body -->
				</div><!-- ./media-body -->
			</div><!-- /.panel -->
		</div><!-- /.col -->
		<?php
			}
		?>
	</div><!-- /.row -->
</div><!-- /.container -->

<?php include_once('template/footer.php') ?>

</body>
</html>