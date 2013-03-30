<?php
// friends.php
// フォロー・フォロワーチェック

require_once("EConfig2.php");
require_once("Twitter.php");

// @Mint_bot
$ignore_user = array("makio1204");

$follow = friends_ids($mint_bot_m);
$follower = followers_ids($mint_bot_m);

if($follow&&$follower){
	$new = array_diff($follower->ids, $follow->ids);
	print("Mint_bot Follow:".count($follow->ids));
	print(" Follower:".count($follower->ids).PHP_EOL);
	$split = array_chunk($new, 100);
	foreach($split as $item){
		$userid = implode(",", $item);
		$arg = array("user_id" => $userid);
		if($result = users_lookup($mint_bot_m, $arg)){
			foreach($result as $user){
				if($user->friends_count >= 2000){
					//print("Follow Count:".$user->friends_count." >= 2000".PHP_EOL);
					continue;
				}
				if($user->follow_request_sent === true){
					//print("Follow Requested.".PHP_EOL);
					continue;
				}
				if(in_array($user->screen_name, $ignore_user) === TRUE){
					//print("Ignore User.".PHP_EOL);
					continue;
				}
				print("☆New Follower:@".$user->screen_name.PHP_EOL);
				$arg = array("user_id" => $user->id);
				if($user->statuses_count === 0){
					print("Tweet Count:".$user->statuses_count." === 0".PHP_EOL);
					users_report_spam($mint_bot_m, $arg);
					continue;
				}
				friendships_create($mint_bot_m, $arg);
				$message = "@".$user->screen_name." さんフォローありがとう！";
				statuses_update($mint_bot_m, $message);
			}
		}
	}
	
	$remove = array_diff($follow->ids, $follower->ids);
	foreach($remove as $user){
		friendships_destroy($mint_bot_m, array("user_id" => $user));
	}
}

// @L_tan
if($follower = followers_ids($mint_bot_l)){
	print("L_tan Follower:".count($follower->ids).PHP_EOL);
	$config = EConfig2::getInstance();
	if(!isset($config->user_list)){
		print("Noset Userlist...".PHP_EOL);
		$split = array_chunk($follower->ids, 100);
		foreach($split as $item){
			$userid = implode(",", $item);
			$arg = array("user_id" => $userid);
			if($result = users_lookup($mint_bot_l, $arg)){
				foreach($result as $user){
					$config->user_list[$user->id] = $user->screen_name;
					print($user->id." -> ".$user->screen_name." added.".PHP_EOL);
				}
			}
		}
	}
	if(!isset($config->follower)){
		print("Noset Follower...".PHP_EOL);
	} else {
		$new = array_diff($follower->ids, $config->follower);
		$remove = array_diff($config->follower, $follower->ids);
		$list = array();
		$split = array_chunk($new+$remove, 100);
		foreach($split as $item){
			$userid = implode(",", $item);
			$arg = array("user_id" => $userid);
			if($result = users_lookup($mint_bot_l, $arg)){
				foreach($result as $user){
					$list[$user->id] = $user->screen_name;
				}
			}
		}
		foreach($new as $user){
			print("☆New Follower:@".$list[$user].PHP_EOL);
		}
		foreach($remove as $user){
			if(isset($list[$user])){
				print("★Remove:@".$list[$user].PHP_EOL);
			} else {
				print("★Remove:".$user."->");
				if(isset($config->user_list[$user])){
					print("@".$config->user_list[$user].PHP_EOL);
				} else {
					print("???".PHP_EOL);
				}
			}
		}
	}
	$config->follower = $follower->ids;
	unset($config);
}

?>
