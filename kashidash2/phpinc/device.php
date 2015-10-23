<?php
/* DBからデバイスデータ取得 PDO版
--------------------------------------------------*/
$stmt = $pdo->query("SELECT * FROM device WHERE d_flg = 0 ORDER BY device_id ASC");
while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
	$device[$row['device_id']] = array(
		'name'			=> $row['name'],
    	'administrator' => $row['administrator'],
    	'os'			=> $row['os'],
    	'version_major'	=> $row['version_major'],
    	'version_minor'	=> $row['version_minor'],
    	'misc'			=> $row['misc'],
    	'img'			=> $row['img'],
    	'create_date'	=> $row['create_date'],
    	'update_date'	=> $row['update_date'],
	);
}









