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
				"ch2623562" => "テイルズ オブ ゼスティリア ザ クロス",                
				"ch2627896" => "超・少年探偵団NEO",
				"ch2628257" => "あいまいみー～Surgical Friends～",
				"ch2628459" => "ちるらん にぶんの壱",
				"ch2628133" => "SUPER LOVERS 2",
				"ch2628353" => "アイドル事変",
				"ch2628127" => "ハンドシェイカー",
				"ch2628132" => "この素晴らしい世界に祝福を！2",
				"ch2628125" => "One Room",
				"ch2628162" => "政宗くんのリベンジ",
				"ch2628126" => "幼女戦記",
				"ch2628239" => "霊剣山 叡智への資格",
				"ch2628354" => "けものフレンズ",
				"ch2628488" => "南鎌倉高校女子自転車部",
				"ch2628355" => "AKIBA’S TRIP -THE ANIMATION- ",
				"ch2628128" => "CHAOS;CHILD",
				"ch2628246" => "小林さんちのメイドラゴン",
				"ch2628131" => "ガヴリールドロップアウト",                
				"ch2628485" => "セイレン",
				"ch2628486" => "ピアシェ～私のイタリアン～",
				"ch2627905" => "弱虫ペダル NEW GENERATION",
				//2017/spring
				"ch2630745" => "デュエル・マスターズ(2017)",
				"ch2620795" => "僕のヒーローアカデミア",
				"ch2630156" => "TVアニメ「進撃の巨人」Season 2",
				"ch2630568" => "武装少女マキャヴェリズム",
				"ch2630626" => "世界の闇図鑑",
				"ch2630541" => "アキンド星のリトル・ペソ",
				"ch2630570" => "ゼロから始める魔法の書",
				"ch2630571" => "ツインエンジェルＢＲＥＡＫ",
				"ch2630529" => "スタミュ(第2期)",
				"ch2630256" => "GRANBLUE FANTASY The Animation",
				"ch2629853" => "ソード・オラトリア",
				"ch2630719" => "笑ゥせぇるすまんNEW",
				"ch2630573" => "エロマンガ先生",
				"ch2630566" => "終末なにしてますか？忙しいですか？救ってもらっていいですか？",
				"ch2630687" => "TVアニメ「つぐもも」",
				"ch2629886" => "フレームアームズ・ガール",
				"ch2630398" => "TVアニメ「サクラクエスト」",
				"ch2630400" => "サクラダリセット",
				"ch2630572" => "ひなこのーと",
				"ch2630349" => "覆面系ノイズ",
				"ch2630711" => "銀の墓守り",
				"ch2630567" => "ロクでなし魔術講師と禁忌教典",
				"ch2630404" => "王室教師ハイネ",
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
