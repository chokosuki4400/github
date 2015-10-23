<?php
//auth
$auth_str = (isset($_GET['auth'])) ? '&auth='.$_GET['auth']: '';
$auth2_str = (isset($_GET['auth'])) ? '?auth='.$_GET['auth']: '';


// HTML head 読み込み
$title = 'KASHIDASHとは' .' | KASHIDASH 弐号機';
$addCss = '<link rel="stylesheet" type="text/css" href="/kashidash2/common/css/print.css" media="print">'."\n";
$addJs = '';
$active = 1;
include_once('template/head.php');

?>
<body>
<?php
include_once('template/header.php');
?>

<div id="contents">
	<section id="main">
		<h2 class="title01">KASHIDASHとは</h2>

<div id="about">
<h3>■『KASHIDASH』とは・・・</h3>

<p class="spc1">『KASHIDASH』とは、端末の貸出状況を管理するサイトです。
借りたい人は必ず登録してください。</p>


<h4>■簡単な使い方</h4>

<h5>★貸出とき</h5>

<p>1.一覧で端末の <span class="btn btn-success btn-xs"><i class="fa fa-lock"></i> 貸出</span> <span class="btn btn-info btn-xs"><i class="fa fa-lock"></i> 貸出</span> というボタンが表示されているものは全て借りられます。<br>
　※端末ステイタスが貸出中のもの借りられません。</p>

<p>2. <span class="btn btn-success btn-xs"><i class="fa fa-lock"></i> 貸出</span> <span class="btn btn-info btn-xs"><i class="fa fa-lock"></i> 貸出</span> ボタンを押すと貸出画面が表示されます。<b class="txColor01">必須項目</b>を全部入力して申請してください。</p>


<p class="spc1">※1　申請後、利用状況が<b><b>貸出</b></b>から <span class="btn btn-danger btn-xs"><i class="fa fa-unlock"></i>&nbsp;返&nbsp;却&nbsp;</span> に代わります。<br>
そして端末ステイタスが <span class="label label-danger labelAccept"><i class="fa fa-exclamation-triangle"></i> 未承認</span> になります。これはまだ管理者が確認していない状態です。<br>
管理者確認後 <span class="label label-success labelAccept">承認済</span> になります。</p>



<h5>★返す時</h5>

<p>1.先程の <span class="btn btn-danger btn-xs"><i class="fa fa-unlock"></i>&nbsp;返&nbsp;却&nbsp;</span> のボタンを押せば返却できます。</p>

<p>2. 返却後、管理者が承認していない場合は端末ステイタスが <span class="label label-danger labelAccept"><i class="fa fa-exclamation-triangle"></i> 返却承認待ち</span> になります。この状態だと貸出ことはできません。</p>
<p>3. 管理者が承認するとステイタスがクリアされ、貸出ことができます。</p>

<h5>★管理者の承認</h5>

<p>1.端末ステイタスが <span class="label label-danger labelAccept"><i class="fa fa-exclamation-triangle"></i> 未承認</span> <span class="label label-danger labelAccept"><i class="fa fa-exclamation-triangle"></i> 返却承認待ち</span> の場合には、デバイスの画像をクリックして、デバイス詳細がメインに行き、承認を行ってください。</p>

</div>

	</section>

	<div class="pagetop"><a href="#">▲PAGE TOPへ</a></div>
</div><!-- #contents -->

<?php include_once('template/footer.php') ?>
</div>
</body>
</html>
