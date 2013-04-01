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
				"ch41" => "GINGA",
				"ch765" => "ニコニコアイマスｃｈ“たるき亭”",
				"ch60036" => "ゆるゆり",
				"ch60198" => "戦姫絶唱シンフォギア",
				"ch60251" => "秘密結社 鷹の爪 NEO",
				"ch60250" => "咲-Saki-阿知賀編 episode of side-A",
				"ch60342" => "トータル・イクリプス",
				// 2012/10
				"ch60388" => "うーさーのその日暮らし",
				"ch60393" => "さくら荘のペットな彼女",
				"ch60383" => "ジョジョの奇妙な冒険",
				"ch60394" => "新世界より",
				"ch60387" => "リトルバスターズ！",
				//2013/01
				"ch60410" => "あいまいみー",
				"ch60415" => "戦勇。",
				"ch60418" => "D.C.3 -ダ・カーポ3-",
				"ch60421" => "俺の彼女と幼なじみが修羅場すぎる",
				"ch60422" => "琴浦さん",
				"ch60423" => "ぷちます！-PETIT IDOLM@STER-",
				"ch60424" => "キューティクル探偵因幡",
				"ch60426" => "まおゆう魔王勇者",
				"ch1457" => "ヤマノススメ",
				"ch1428" => "gdgd妖精s",
				"ch1426" => "僕の妹は「大阪おかん」",
				"ch1494" => "まんがーる！",
				"ch2526031" => "THE UNLIMITED 兵部京介",
				"ch2526032" => "AMNESIA",
				"ch2526034" => "ラブライブ！",
				"ch2526096" => "閃乱カグラ",
				"ch2526097" => "ビビッドレッド・オペレーション",
				"ch2526098" => "ヘタリア The Beautiful World",
				"ch2526118" => "直球表題ロボットアニメ",
				"ch2567623" => "ささみさん＠がんばらない",
				//2013/04
				"ch2563775" => "あいうら",
				"ch2568046" => "惡の華",
				"ch2568763" => "うたの☆プリンスさまっ♪ マジLOVE2000％",
				"ch2567086" => "俺の妹がこんなに可愛いわけがない。",
				"ch2567081" => "革命機ヴァルヴレイヴ",
				"ch2565318" => "銀河機攻隊 マジェスティックプリンス",
				"ch2566378" => "血液型くん！",
				"ch2565327" => "進撃の巨人",
				"ch2567313" => "翠星のガルガンティア",
				"ch2566372" => "スパロウズホテル",
				"ch2528113" => "デート・ア・ライブ",
				"ch2567330" => "DEVIL SURVIVOR2 the ANIMATION",
				"ch2567097" => "とある科学の超電磁砲S",
				"ch2568047" => "波打際のむろみさん",
				"ch2562762" => "這いよれ！ニャル子さんＷ",
				"ch2567093" => "はたらく魔王さま！",
				"ch2568219" => "ハヤテのごとく！ Cuties",
				"ch2567319" => "百花繚乱 サムライブライド",
				"ch2568065" => "プリティーリズム・レインボーライブ",
				"ch2568218" => "ゆゆ式",
				"ch2568048" => "よんでますよ、アザゼルさん。Z",
				"ch2527996" => "RDG レッドデータガール",
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
		$title = htmlspecialchars_decode($entry->title);
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
			$title = htmlspecialchars_decode($thumbinfo->thumb->title);
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
									"#nico".$id." #".$smid);
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
