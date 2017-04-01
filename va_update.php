<?php
// va_update.php
// 声優プロフィールチェック

require_once("Twitter.php");
$savefile = "va_update.txt";
$last = unserialize(file_get_contents($savefile));
$results = friends_list($va_bot, array("screen_name" => "va_update",
										"skip_status" => "true",
										"count" => "200"));
file_put_contents($savefile, serialize($results));
if($last === FALSE) {
	print("savefile not found...".PHP_EOL);
	exit();
}
foreach($last->users as $old_user) {
	print($old_user->screen_name." check...".PHP_EOL);
	$new_user = null;
	foreach($results->users as $user) {
		if($old_user->id == $user->id) {
			$new_user = $user;
		}
	}
	if(is_null($new_user)) continue;
	check_profile($old_user, $new_user, "name", "名前");
	check_profile($old_user, $new_user, "location", "場所");
	check_profile($old_user, $new_user, "url", "URL");
	check_profile($old_user, $new_user, "description", "自己紹介");
	check_profile($old_user, $new_user, "profile_banner_url", "ヘッダー画像", true);
	check_profile($old_user, $new_user, "profile_image_url", "アイコン画像", true);
	print(PHP_EOL);
}

function check_profile($old, $new, $item, $name, $image = false) {
	print("---".$item."---".PHP_EOL);
	print($old->$item.PHP_EOL);
	print("---changed?---".PHP_EOL);
	print($new->$item.PHP_EOL);
	print("------".PHP_EOL);
	if($old->$item != $new->$item) {
		update_tweet($old->screen_name, $name, $new->$item, $image);
	}
}

function update_tweet($screen_name, $item, $contents, $image = false) {
	global $va_bot;
	$tweet = "【".$screen_name.":".$item." updated】".PHP_EOL;
	$arg = array();
	if($image) {
		$arg["media_ids"] = get_media_id($contents);
	} else {
		$length = 140 - mb_strlen($tweet);
		$tweet .= mb_substr($contents, 0, $length);
		if(mb_strlen($tweet) === 140) $tweet = mb_substr($tweet, 0, 139)."…";
	}
	print($tweet);
	statuses_update($va_bot, $tweet, $arg);
}
function get_media_id($image_url) {
	global $va_bot;
	if(strpos($image_url, "profile_images") !== FALSE){
		$image_url = str_replace("_normal", "", $image_url);
	}
	print("get image (".$image_url.") and upload...");
	$data = file_get_contents($image_url);
	$media = media_upload($va_bot, base64_encode($data));
	return $media->media_id_string;
}
?>
