<?php
/******************************
 画面名：DB UPDATE処理
 作成日：2014.04.12
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/
// デバッグ
$VC_post_echo = false;
$VC_get_echo  = false;
$debug_str ="";

// 共通設定読み込み
require_once('phpinc/config.php');

// 処理タイプ取得
$type = (isset($ING_type)) ? $ING_type : null;
$type = (isset($INP_type)) ? $INP_type : $type;

// initialize
$title			= "DB UPDATE";
$uploadErr		= 0;
$errStr			= '';
$file_name		= '';
$uploadFileSize = 10000;//アップロードできるファイルサイズ（単位：byte）


/* device updete 処理
--------------------------------------------------*/
if(($type == 'device') && ($_FILES['file_device']['error']==0)){
	$title		= "Device Update";
	$uploadDir	= $_SERVER['DOCUMENT_ROOT'].'/kashidash2/device/';
	
	// フォルダの書き込み権限チェック
	if(!is_writable($uploadDir)){
		$uploadErr = 2;
		$errStr = 'ファイルの書き込み権限がありません';
	} else {// csvファイルのアップロード処理
		// アップロードファイル名決定
		$name		= 'device'.date('Ymd-His').'.csv';
		$uploadFile	= $uploadDir.$name;
		
		try{
			// エラー判定
			if(!isset($_FILES['file_device']['error']) || is_array($_FILES['file_device']['error'])){
				throw new RuntimeExeption('パラメータが正しくありません');
				$uploadErr = 2;
			}
			switch($_FILES['file_device']['error']){
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException('ファイルは送信されませんでした');
					$uploadErr = 2;
					break;
				case UPLOAD_ERR_INI_SIZE:
		        case UPLOAD_ERR_FORM_SIZE:
		            throw new RuntimeException('最大ファイルサイズを超えています（10kb）');
		            $uploadErr = 2;
		            break;
		        default:
		            throw new RuntimeException('不明なエラーです');
		            $uploadErr = 2;
			}
			// アップロードファイルサイズチェック
			if ($_FILES['file_device']['size'] > $uploadFileSize) {
		        throw new RuntimeException('最大ファイルサイズを超えています（10kb）');
		        $uploadErr = 2;
		    }
/*
		    $finfo = new finfo(FILEINFO_MIME_TYPE);
		    if (false === $ext = array_search(
		        $finfo->file($_FILES['file_device']['tmp_name']),
		        array(
		            'csv' => 'text/comma-separated-values',
		            'tsv' => 'text/tab-separated-values',
		            'txt' => 'text/plain',
		        ),
		        true
		    )) {
		        throw new RuntimeException('ファイルフォーマットが正しくありません');
		    }
*/
/*
			if (!move_uploaded_file($_FILES['file_device']['tmp_name'],
		        sprintf($uploadFile,
		            sha1_file($_FILES['file_device']['tmp_name']),
		            $ext
		        )
		    )) {
		        throw new RuntimeException('ファイルのアップロードに失敗しました');
		        $uploadErr = 2;
		    } else {
			    $res = chmod($uploadFile, 0777);
			    if($res){
				    throw new RuntimeException('ファイルの権限を変更できませんでした。');
				    $uploadErr = 2;
			    }
		    }
*/
		    
		    $file_contents = file_get_contents($_FILES['file_device']['tmp_name']);
		    touch($uploadFile);
			if(!chmod($uploadFile, 0777)){
				throw new RuntimeException('ファイルの権限を変更できませんでした。');
				$uploadErr = 2;
			}
		    if(!file_put_contents($uploadFile, $file_contents)){
			    throw new RuntimeException('ファイルのアップロードに失敗しました。');
			    $uploadErr = 2;
		    }
		    
			$uploadErr = 1;
			$file_name = $_FILES['file_device']['name'];
			$errStr = $file_name.' のアップロードに成功しました<br>';
			$errStr .= '（'.$uploadFile.'）<hr>';
			
		} catch (RuntimeException $e){
			$uploadErr = 2;
			$errStr = $e->getMessage();
		}
	}
}

// データベース更新
if($uploadErr == 1){
	$data = $file_contents;
	$data = mb_convert_encoding($data, 'UTF-8','sjis-win');
	$temp = tmpfile();
	$csv = array();
	
	fwrite($temp, $data);
	rewind($temp);

	setlocale(LC_ALL, 'ja_JP.UTF-8');
	while(($data = fgetcsv($temp)) !== false){
		$csv[] = $data;
	}
	fclose($temp);
	$updateCount = 0;
	$insertCount = 0;
	foreach($csv as $key => $val){
		if(($key != 0) && ($val[0] != '')){
		
			$sql = 'SELECT C>0 AS IS_EXIST FROM(SELECT count( * ) AS C FROM device WHERE device_id=:device_id) AS DAMMY';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':device_id',$val[0]);
			$stmt->execute();
			$isExsit = 0;
			foreach($stmt as $row){
				$isExsit = $row['IS_EXIST'];
			}
			// 既にあればUPDATEなければINSERT
			if($isExsit == 1){
				$sql = 'UPDATE `device` SET `d_flg`=:d_flg,
											`administrator`=:administrator,
											`name`=:name,
											`os`=:os,
											`version_major`=:version_major,
											`version_minor`=:version_minor,
											`misc`=:misc,
											`img`=:img,
											`update_date`=:update_date
										WHERE `device_id`=:device_id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':device_id'		,$val[0]);
				$stmt->bindParam(':d_flg'			,$val[1]);
				$stmt->bindParam(':administrator'	,$val[2]);
				$stmt->bindParam(':name'			,$val[3]);
				$stmt->bindParam(':os'				,$val[4]);
				$stmt->bindParam(':version_major'	,$val[5]);
				$stmt->bindParam(':version_minor'	,$val[6]);
				$stmt->bindParam(':misc'			,$val[7]);
				$stmt->bindParam(':img'				,$val[8]);
				$stmt->bindParam(':update_date'		,date('Y-m-d H:i:s'));
				try{
					$ret = $stmt->execute();
					if (!$ret) {
				        die("error");
				    }
				}catch (PDOException $e) {
				    print "Exception";
				    print $e->getMessage();
				}
				//$debug_str .=$ret; 
				$updateCount ++;
			} else {
				$sql = 'INSERT INTO device (device_id,d_flg,administrator,name,os,version_major,version_minor,misc,img,create_date) VALUES  (:device_id,:d_flg,:administrator,:name,:os,:version_major,:version_minor,:misc,:img,:create_date)';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':device_id'		,$val[0]);
				$stmt->bindParam(':d_flg'			,$val[1]);
				$stmt->bindParam(':administrator'	,$val[2]);
				$stmt->bindParam(':name'			,$val[3]);
				$stmt->bindParam(':os'				,$val[4]);
				$stmt->bindParam(':version_major'	,$val[5]);
				$stmt->bindParam(':version_minor'	,$val[6]);
				$stmt->bindParam(':misc'			,$val[7]);
				$stmt->bindParam(':img'				,$val[8]);
				$stmt->bindParam(':create_date'		,date('Y-m-d H:i:s'));
				$stmt->execute();
				$insertCount ++;
			}
		}
	}
	$errStr .= ($insertCount != 0) ? "$insertCount 件のデータが追加されました<br>":'';
	$errStr .= ($updateCount != 0) ? "$updateCount 件のデータが更新されました":'';
	//$stmt = $pdo->query();
	
	
	// verifier update
	$updatVCount = 0;
	foreach($csv as $key => $val){
		if($val[0] != '管理番号'){
			$sql = 'UPDATE verifier SET device_id = :device_id WHERE item = :item';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':device_id', $val[0]);
			$stmt->bindParam(':item', $val[3]);
			$stmt->execute();
			//echo "deviceId: {$val[0]} / item: {$val[3]}<br>\n";
		}
	}
}

/* CSV Download 処理
--------------------------------------------------*/
if($type == 'download'){
	// 設定
	$csvFileName = "device_list.csv";
	$csv = array();
	$csv = array(
		array('管理番号','削除フラグ','管理グループ','機種名','OS','メジャーバージョン','サブバージョン','備考','画像ファイル名','作成日','更新日'),
	);
	
	// DBからデータ取得
	$sql = 'SELECT * FROM device ORDER BY device_id ASC';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
	while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
		$tmp[] = array(
			$row['device_id'],
			$row['d_flg'],
	    	$row['administrator'],
	    	$row['name'],
	    	$row['os'],
	    	$row['version_major'],
	    	$row['version_minor'],
	    	$row['misc'],
	    	$row['img'],
	    	$row['create_date'],
	    	$row['update_date']
		);
	}
	$csv = array_merge($csv,$tmp);
	foreach($csv as $row){
		$cols = array();
		foreach($row as $col){
			$cols[] = mb_convert_encoding($col, 'Shift_JIS', 'UTF-8');
		}
		$rows[] = $cols;
	}
	$debug_str = $rows;
	$outputFile = $_SERVER['DOCUMENT_ROOT'].'/kashidash2/device/'.$csvFileName;
	touch($outputFile);
	chmod($outputFile, 0777);
	$fp = fopen($outputFile,'w');
	
	foreach($rows as $line){
		fputcsv($fp, $line, ',', '"');
	}
	
	fclose($fp);
	header("Content-Type: application/csv");
	header("Content-Disposition: attachment; filename=".$csvFileName);
	header("Content-Length:".filesize($outputFile));
	readfile($outputFile);
}


// HTML head 読み込み
$addCss = '<link rel="alternate" media="print" href="indexprint.php" type="application/pdf">'."\n";
$active = 0;

include_once('template/head.php');
?>
<body>
<?php 
include_once('template/header.php');
?>
<div class="container">
	<div class="row">
		<h1 class="col-xs-12"><?php echo $title ?></h1>
		<pre><?php //print_r($_FILES) ?></pre>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-12">
			<h2>Device UPDATE</h2>
			<?php
			switch($uploadErr){
				case 1:
					echo '<aside id="news" class="alert alert-info">'.$errStr.'</aside>';
					break;
				case 2:
					echo '<aside id="news" class="alert alert-danger">'.$errStr.'</aside>';
					break;
				case 0:
				default:
				;
			}
			?>
		</div>
	</div>
	<div class="row">
		<form action="#" method="post" enctype="multipart/form-data" name="form_device">
			<div class="col-xs-8">
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $uploadFileSize ?>">
				<input type="hidden" name="type" value="device">
				<input type="hidden" name="file_name" value="<?php echo $name ?>">
				<input type="file" name="file_device" id="file_device" style="display:none;" value="">
				<div class="input-group input-group-sm">
					<span class="input-group-btn">
						<a type="button" class="btn btn-default" onclick="$('#file_device').click();"><i class="glyphicon glyphicon-folder-open"></i></a>
					</span>
					<span id="cover" class="form-control">ファイルを選択</span>
				</div>
			</div>
			<div class="col-xs-4">
				<button type="submit" value="ファイルをアップロード" class="btn btn-warning btn-sm" disabled="disabled" id="file_device_upload"><i class="glyphicon glyphicon-cloud-upload"></i> CSV をアップロード</button>
			</div>
			<div class="col-xs-12" style="margin-top:20px;">
				<aside id="news" class="alert alert-info">
					※アップロードするファイル名は自動でリネームされます。
				</aside>
			</div>
		</form>
		
	</div><!-- /Device Update -->
	<hr>
	<div class="row">
		<div class="col-xs-12">
			<h2>Download Device List</h2>
		</div>
		<form name="download" action="#" method="post" enctype="multipart/form-data">
			<div class="col-xs-12">
				<input type="hidden" name="type" value="download">
				<button type="submit" value="CSVをダウンロード" class="btn btn-warning btn-sm" id="file_device_upload"><i class="glyphicon glyphicon-cloud-download"></i> CSV をダウンロード</button>
			</div>
<!--
			<div class="col-xs-12" style="margin-top:20px;">
				<aside id="news" class="alert alert-info">
					※アップロードするファイル名は自動でリネームされます。
				</aside>
			</div>
-->
		</form>
		
	</div><!-- /Download Device list -->
	<hr>
	<!--
	<div class="row">
		<div class="col-xs-12">
			<h2>Add Device</h2>
		</div>
		<div class="col-xs-12">
			<form name="addDevice" action="#" method="post" enctype="multipart/form-data" class="form-inline" role="form">
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $uploadFileSize ?>">
				<input type="hidden" name="type" value="device">
				<input type="hidden" name="file_name" value="<?php echo $name ?>">
				<input type="file" name="file_device" id="file_device" style="display:none;" value="">
				
				<div class="form-group">
					<label class="sr-only" for="device_id">デバイスID</label>
					<input type="text" class="form-control" id="device_id" name="device_id" maxlength="10" placeholder="e.g. WI-001" value="">
				</div>
				<div class="form-group">
					<label class="sr-only" for="device_id">デバイスID</label>
					<input type="text" class="form-control" id="device_id" name="device_id" maxlength="10" placeholder="e.g. WI-001" value="">
				</div>
			</form>
		</div>
	</div>-->
	<!-- / ADD DEVICE -->
</div>

<?php include_once('template/footer.php') ?>
<script>
$(function(){
	$('#file_device').on('change',function(){
		$('#cover').html($(this).attr('value'));
		$('#file_device_upload').removeAttr('disabled');
	});
});
</script>
</body>
</html>