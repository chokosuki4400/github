<?php
/******************************
 画面名：データ取得処理
 作成日：2014.03.21
 更新日：
 作成者：松村 y.matsumura@ejworks.com
 ******************************/

$VC_post_echo = (isset($VC_post_echo)) ? $VC_post_echo : false;
$VC_get_echo  = (isset($VC_get_echo))  ? $VC_get_echo  : false;

//******************************
// POST の値の取得
//******************************
if(is_array($_POST)){
  foreach ($_POST as $name => $value){
    $key_name = "INP_" . $name;
    // $valueが個データの場合
    if (!is_array($value)) {
      ${$key_name} = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      ${$key_name} = stripslashes(${$key_name});
    // 配列データの場合
    } else {
      ${$key_name} = array_map("htmlspecialchars", $value);
    }
      if($VC_post_echo == true){
      echo $key_name . " : " . ${$key_name} . "<br>";
    }
  }
}

//******************************
// GET の値の取得
//******************************
if(is_array($_GET)){
  foreach ($_GET as $name => $value){
    $key_name = "ING_" . $name;
    // $valueが個データの場合
    if (!is_array($value)){
      ${$key_name} = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      ${$key_name} = stripslashes(${$key_name});
    // 配列データの場合
    } else {
      ${$key_name} = array_map("htmlspecialchars", $value);
    }
      if($VC_get_echo == true){
      echo $key_name . " : " . ${$key_name} . "<br>";
    }
  }
}

//******************************
// SESSION の値の取得
//******************************
/*
if(is_array($_SESSION)){
  foreach($_SESSION as $name => $value){
    $key_name = "INS_" . $name;
    // $valueが個データの場合
    if (!is_array($value)) {
      ${$key_name} = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      ${$key_name} = stripslashes(${$key_name});
    // 配列データの場合
    } else {
      ${$key_name} = array_map("htmlspecialchars", $value);
    }
    if($VC_session_echo == true){
      echo $key_name . " : " . ${$key_name} . "<br>";
    }
  }
}
*/