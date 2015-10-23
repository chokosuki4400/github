<footer class="container-narrow">
	<p>copyright &copy; 2012-<?php echo date('Y') ?> KASHIDASH all rights reserved.</p>
</footer>

<script src="/kashidash2/common/lib/jquery-1.8.2.min.js"></script>
<script src="/kashidash2/common/js/bootstrap.min.js"></script>
<script src="/kashidash2/common/js/jQueryAutoHeight.js"></script>
<script src="/kashidash2/common/js/jquery.prettyPopin.js"></script>
<script src="/kashidash2/common/js/common.js"></script>

<script src="/kashidash2/common/lib/posabsolute-jQuery-Validation/jquery.validationEngine.js"></script>
<script src="/kashidash2/common/lib/posabsolute-jQuery-Validation/jquery.validationEngine-ja.js"></script>
<script type="text/javascript">
$(function(){
	var v = $("#lendInput");
	if(v.length > 0){
		v.validationEngine();
	}
});
</script>
<script>
	$(function(){
		$('.btn_return').click(function(){
			var itemID = $(this).attr('id');
			itemID = itemID.substring(7);
			var dataID = $(this).attr('data-id');
			if(window.confirm('返却しますか？')){
				location.href='return.php?&id=' + dataID;
			}else{
				alert('キャンセルしました。');
			}
		});
	});
</script>
<?php if(!empty($returnSccess)): ?>
<script>
	$(function(){
		var newUrl = location.href;
		newUrl = newUrl.substring(0,newUrl.lastIndexOf('?'));
		alert('返却処理を実行しました。');
		location.href = newUrl;
	});
</script>
<?php endif; ?>