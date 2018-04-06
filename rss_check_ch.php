<?php
// rss_check_ch.php
// ニコニコ動画RSSチェック(チャンネル)

require_once("EConfig.php");
require_once("Nicovideo.php");

$config = EConfig::getInstance();
if(!isset($config->last_rss_ch)){
	$config->last_rss_ch = time();
	print("Noset last_rss_ch.".PHP_EOL);
	return;
}
$check = $config->last_rss_ch;
unset($config);

print(date("Y/m/d H:i:s",$check)."以降のマイリスチェック開始".PHP_EOL);
$list_array = array(
				"ch253" => "初音ミク ―Project DIVA―",
				"ch312" => "アニメロチャンネル",
				"ch60045" => "アイドルマスター",
				//2013/01
				"ch2526034" => "ラブライブ！",
				//2014/winter
				"ch2585573" => "未確認で進行形",
				//2014/spring
				"ch2591203" => "ご注文はうさぎですか？",
				"ch2590587" => "ラブライブ！2期",
				//2014/summer
				"ch2595174" => "ハナヤマタ",
				"ch2594336" => "美少女戦士セーラームーンCrystal",
				//2014/autumn
				"ch2599672" => "繰繰れ！コックリさん",
				"ch2599669" => "結城友奈は勇者である",
				//2015/winter
				"ch2602382" => "アイドルマスターシンデレラガールズ",
				//2015/spring
				"ch2607110" => "SHOW BY ROCK!!",
				"ch2611643" => "ウルトラスーパーアニメタイム",
				// 2015/autumn
				"ch2614611" => "おそ松さん",
				"ch2614174" => "ご注文はうさぎですか？？",
				// 2016/winter
				"ch2617580" => "おじさんとマシュマロ",
				//2016/summer
				"ch2623557" => "ラブライブ！サンシャイン!!",
				"ch2623457" => "NEW GAME!",
				//2017/winter
				"ch2628354" => "けものフレンズ",
				//2018/spring
				"ch2636322" => "こみっくがーるず",
				"ch2636268" => "TVアニメ「ペルソナ５」",
				"ch2636105" => "Caligula-カリギュラ-",
				"ch2633508" => "「鬼灯の冷徹」第弐期",
				"ch2636032" => "ハイスクールD×D HERO",
				"ch2636065" => "あまんちゅ！ あどばんす",
				"ch2636132" => "東京喰種:re",
				"ch2636136" => "ルパン三世 PART5",
				"ch2636334" => "ありすorありす",
				"ch2636133" => "多田くんは恋をしない",
				"ch2636267" => "ラストピリオド ‐終わりなき螺旋の物語‐",
				"ch2636308" => "パズドラ",
				"ch2636033" => "シュタインズ・ゲート ゼロ",
				"ch2636323" => "魔法少女 俺",
				"ch2636433" => "キャプテン翼",
				"ch2636321" => "されど罪人は竜と踊る",
				"ch2636146" => "メガロボクス",
				"ch2636333" => "３Ｄ彼女　リアルガール",
				"ch2636222" => "あっくんとカノジョ",
				"ch2634623" => "Butlers 千年百年物語",
				"ch2636035" => "ヒナまつり",
				"ch2636034" => "フルメタル・パニック！ Invisible Victory",
				"ch2636115" => "ウマ娘 プリティーダービー",
				"ch2636138" => "信長の忍び-姉川・石山篇-",
				"ch2636031" => "ソードアート・オンライン オルタナティブ ガンゲイル・オンライン",
				"ch2636335" => "ニル・アドミラリの天秤",
				"ch2636273" => "デュエル・マスターズ！",
				"ch2636257" => "銀河英雄伝説 Die Neue These",
				"ch2636309" => "デビルズライン",
				"ch2636208" => "美男高校地球防衛部HAPPY KISS！",
				"ch2635845" => "甘い懲罰 私は看守専用ペット",
				);
$config = EConfig::getInstance();
$config->last_rss_ch = time();
print("次は".date("Y/m/d H:i:s",$config->last_rss_ch)."以降をチェック".PHP_EOL);
unset($config);

$check_count = 0;
foreach($list_array as $id => $name){
	$url = "http://ch.nicovideo.jp/".$id."/video?sort=f&order=d&rss=2.0";
	if(($ret = file_get_contents($url)) === false){
		continue;
	}
	if(($xml = simplexml_load_string($ret)) === false){
		continue;
	}
	
	if(!isset($xml->channel)){
		print($name." Fetch Failed...".PHP_EOL);
		continue;
	}
	$check_count++;
	if($check_count%10 == 0){
		print("#");
	} else {
		print("+");
	}
	foreach($xml->channel->item as $entry){
		$title = htmlspecialchars_decode($entry->title, ENT_QUOTES);
		$videoid = basename($entry->link);
		if(preg_match("/<strong class=\"nico-info-length\">(.*?)<\/strong>/u",
						$entry->content, $match) === 1){
			$time = "(".$match[1].")";
		} else {
			$time = "";
		}
		$ret = file_get_contents("http://ext.nicovideo.jp/api/getthumbinfo/".$videoid);
		if($ret === false){
			break;
		}
		if(($thumbinfo = simplexml_load_string($ret)) === false){
			continue;
		}
		$smid = "";
		if($thumbinfo["status"] != "ok"){
			print($smid." GetThumbInfo Failed...".PHP_EOL);
			break;
		} else if($thumbinfo->thumb->first_retrieve == 0){
			print($smid." FirstRetrieve Zero...".PHP_EOL);
			break;
		} else {
			$title = htmlspecialchars_decode($thumbinfo->thumb->title, ENT_QUOTES);
			$smid = $thumbinfo->thumb->video_id;
			if(isset($thumbinfo->thumb->length)){
				$time = "(".$thumbinfo->thumb->length.")";
			}
			$posttime = $thumbinfo->thumb->first_retrieve;
		}
		if($smid == ""){
			$smid = $videoid;
		}
		print($videoid." -> ".$smid." : ");
		if(strtotime($posttime) >= $check){
			print(date("Y/m/d H:i:s", strtotime($entry->published)));
			print(" > ");
			print(date("Y/m/d H:i:s", $check).PHP_EOL);
			$message = make_message("まぁ、チャンネル動画",
									$title,
									$time,
									"が投稿されたようですよ。",
									"http://nico.ms/".$videoid,
									"#nicoch #".$smid);
			tweet_message($mint_bot_m, $message, null, 0);
			nicocache_fetch($videoid);
		} else {
			print(date("Y/m/d H:i:s", strtotime($posttime)));
			print(" < ");
			print(date("Y/m/d H:i:s", $check).PHP_EOL);
			break;
		}
	}
	sleep(1);
}
print(PHP_EOL.$check_count."のチャンネル動画をチェックしました".PHP_EOL);

?>
