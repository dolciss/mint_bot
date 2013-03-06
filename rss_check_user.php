<?php
// rss_check_user.php
// ニコニコ動画RSSチェック(ユーザー)

require_once("EConfig.php");
require_once("Nicovideo.php");

$config = EConfig::getInstance();
if(!isset($config->last_rss)){
	$config->last_rss = time();
	print("Noset last_rss.".PHP_EOL);
	return;
}
$check = $config->last_rss;
unset($config);

print(date("Y/m/d H:i:s",$check)."以降のマイリスチェック開始".PHP_EOL);
$list_array = array(
				//"L-tanテスト用" => "17076218",
				// その他
				"QubicLoopさん(踊れます)" => "19515449",
				"QubicLoopさん(単発)" => "22638603",
				// 演奏してみた
				"soraさん" => "4784178",
				"maruさん" => "3894886",
				// 動画系
				"わかむらP" => "1399500",
				"まさたかさん" => "13058155",
				"砂吹さん" => "3602796",
				"Aono.Yさん" => "21583727",
				// VOCALOID系
				"kzさん(livetune)" => "3091300",
				"かじゅきさん" => "2677415",
				"くちばしさん" => "5646577",
				"HALLさん(ミニスカP)" => "4720246",
				"minatoさん(流星P)" => "4348647",
				"dorikoさん(きりたんP)" => "4586898",
				"ラマーズP" => "7962519",
				"OneRoomさん(ジミーサムP)" => "8538985",
				"SHIKIさん" => "6421081",
				"SAMさん(samfree)" => "3765386",
				"ヽ(ヽ･∀∀･)にこP" => "4266743",
				"ryuryuさん(びにゅP)その1" => "11892274",
				"ryuryuさん(びにゅP)その2" => "14090300",
				"ちゃぁさん" => "3234823",
				"BETTIさん(EasyPopさん)" => "12749479",
				"miumixさん" => "12898005",
				"Junkさん(定額P)" => "16570237",
				"sunzriverさん(すんｚりヴぇｒP)" => "6980684",
				"チータンさん" => "14776433",
				"のぼる↑さん" => "6082765",
				"ずどどんさん" => "6871196",
				"SWANTONEさん" => "14537708",
				"恋竹林さん" => "15416475",
				"SmileRさん" => "22380461",
				"なかさん(チーズP)" => "13062744",
				"瑞智士記さん" => "22729849",
				"" => "",
				);
$config = EConfig::getInstance();
$config->last_rss = time();
print("次は".date("Y/m/d H:i:s",$config->last_rss)."以降をチェック".PHP_EOL);
unset($config);

$check_count = 0;
foreach($list_array as $name => $id){
	if(!is_numeric($id)){
		continue;
	}
	$url = "http://www.nicovideo.jp/mylist/".$id."?rss=atom&sort=6";
	if(($ret = file_get_contents($url)) === false){
		break;
	}
	if(($xml = simplexml_load_string($ret)) === false){
		continue;
	}
	
	if(!isset($xml->title)){
		print($name." Fetch Failed...".PHP_EOL);
		continue;
	}
	$check_count++;
	if($check_count%10 == 0){
		print("#");
	} else {
		print("+");
	}
	foreach($xml->entry as $entry){
		$title = htmlspecialchars_decode($entry->title);
		$smid = basename($entry->link["href"]);
		if(preg_match("/<strong class=\"nico-info-length\">(.*?)<\/strong>/u",
						$entry->content, $match) === 1){
			$time = "(".$match[1].")";
		} else {
			$time = "";
		}
		if(strtotime($entry->published) >= $check){
			print(date("Y/m/d H:i:s", strtotime($entry->published)));
			print(" > ");
			print(date("Y/m/d H:i:s", $check).PHP_EOL);
			$message = make_message("まぁ、".$name."のマイリストに",
									$title,
									$time,
									"が追加されたようですよ。",
									"http://nico.ms/".$smid,
									"#".$smid." #nicovideo");
			tweet_message($mint_bot_m, $message, null, 0);
		} else {
			break;
		}
	}
	sleep(1);
}
print(PHP_EOL.$check_count."のマイリスをチェックしました".PHP_EOL);

?>