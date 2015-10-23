<?php
//auth
$auth_str = (isset($_GET['auth'])) ? '&auth='.$_GET['auth']: '';
$auth2_str = (isset($_GET['auth'])) ? '?auth='.$_GET['auth']: '';


// HTML head 読み込み
$title = 'バージョン履歴' .' | KASHIDASH 弐号機';
$addCss = '<link rel="stylesheet" type="text/css" href="/kashidash2/common/css/print.css" media="print">'."\n";
$addJs = '';
$active = 2;
include_once('template/head.php');

?>
<body>
<?php 
include_once('template/header.php');
?>

<div class="container">
	<section class="grid_12">
		<h2 class="title01">バージョン履歴</h2>
		
		<dl class="history">
			<dt>v0.4</dt>
			<dd>デバイスをマスター化</dd>
			<dd>ユニークキーをデバイス名からデバイスIDに変更</dd>
			<dt>v0.3</dt>
			<dd>印刷用ページをPDF形式に変更</dd>
			<dd>フォントアイコンを仕様</dd>
			<dt>v0.2</dt>
			<dd>CTとSLPのカラーリングを統一</dd>
			<dd>軽微なバグ修正</dd>
			<dt>v0.1</dt>
			<dd>とりあえずのレイアウト変更対応</dd>
		</dl>


	</section>
	
	<div class="pagetop"><a href="#">▲PAGE TOPへ</a></div>
</div><!-- #contents -->

<?php include_once('template/footer.php') ?>
</div>
</body>
</html>
