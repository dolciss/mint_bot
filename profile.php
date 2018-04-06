<?php
// profile.php
// 曜日毎にプロフィール画像を変更する

require_once("Twitter.php");

if(idate("H") !== 0){
	//0時のみ発動
	exit("not midnight".PHP_EOL);
}
/*
$rand = date("D");
*/
$rand = rand(1, 96);
if($rand == 23) $rand = 13;
if($rand == 24) $rand = 34;
if($rand == 47) $rand = 44;
if($rand == 48) $rand = 60;
$image = "/home/forte/bot/image/".$rand.".png";
$data = file_get_contents($image);
if(account_update_profile_image($mint_bot_l ,base64_encode($data))){
	print($rand." profile image updated.".PHP_EOL);
}

?>
