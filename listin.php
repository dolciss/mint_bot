<?php
// listin.php
// リスト追加

require_once("Twitter.php");
print(date("Y/m/d H:i:s")." List Check".PHP_EOL);
$results = lists_members($mint_bot_l, array("owner_screen_name" => "L_tan",
											"slug" => "26",
											"count" => "5000",
											"skip_status" => "true"));
$members = array();
foreach($results->users as $user){
#	print($user->id_str." => @".$user->screen_name.PHP_EOL);
	$members[] = $user->id_str;
}
print(count($members)." members.".PHP_EOL);

$results = search_tweets($mint_bot_l, 
						"#毎月26日はみくりーなの日 exclude:retweets",
						array("result_type" => "recent", "count" => "100"));
$users = array();
foreach($results->statuses as $status){
	print($status->user->name . "(@" . $status->user->screen_name .":");
	print($status->user->id_str . ") => ");
	print("https://twitter.com/statuses/" . $status->id_str . PHP_EOL);
	$users[] = $status->user->id_str;
}

foreach($users as $user) {
	if(!in_array($user, $members)){
		$arg = array("owner_screen_name" => "L_tan",
					"slug" => "26",
					"user_id" => $user);
		$results = lists_members_create($mint_bot_l, $arg);
		print("user:".$user." added.".PHP_EOL);
		$members[] = $user;
	}
}

file_put_contents("log/listin.csv", 
					date("Y/m/d H:i").",".count($members).PHP_EOL,
					FILE_APPEND);

?>
