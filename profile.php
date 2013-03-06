<?php
// profile.php
// 曜日毎にプロフィール画像を変更する

require_once("Twitter.php");

if(idate("H") !== 0){
	//0時のみ発動
	exit("not midnight".PHP_EOL);
}
$image = "/home/forte/bot/image/".date("D").".png";
$data = file_get_contents($image);
if(account_update_profile_image($mint_bot_l ,base64_encode($data))){
	print(date("D").". profile image updated.".PHP_EOL);
}

?>
