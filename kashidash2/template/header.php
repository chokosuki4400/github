<?php
$navActiveClass = ' class="active"';
$active = (isset($active)) ? $active: '';
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/kashidash2/"><i class="fa fa-tablet"></i> <i class="fa fa-mobile"></i> KASHIDASH 弐号機 β v0.4</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li<?php if($active == 1) echo $navActiveClass ?>><a href="about.php">How to カシダッシュ</a></li>
				<li<?php if($active == 2) echo $navActiveClass ?>><a href="version.php">バージョン履歴</a></li>
				<?php if($authCheck){?>
				<li<?php if($active == 3) echo $navActiveClass ?>><a href="db_update_patch.php">デバイスアップデート</a></li>
				<?php } ?>
			</ul>
		</div><!--/.navbar-collapse -->
	</div>
</div>
<?php
if( $_SERVER['PHP_SELF'] != '/kashidash2/index.php'){ ?>
<div class="container alpha noprint">
	<div class="btnBack">
		<a class="btn btn-primary btn-sm" href="/kashidash2/"> <i class="fa fa-arrow-circle-left"></i>　戻　る　</a>
		<?php
			if( $_SERVER['PHP_SELF'] == '/kashidash2/detail.php'){ ?>
		<button class="btn btn-default btn-sm" onclick="javascript:window.open('/kashidash2/detailprint.pdf');" style="float:right"> <i class="fa fa-print"></i> 印刷用PDF</button>
		<?php }; ?>

	</div>
</div>
<?php };

?>