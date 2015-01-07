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
				"ch312" => "アニメロチャンネル",
				"ch60045" => "アイドルマスター",
				//2013/01
				"ch2526034" => "ラブライブ！",
				//2013/autumn
				"ch2576710" => "てさぐれ！部活もの",
				//2014/winter
				"ch2581668" => "うーさーのその日暮らし 覚醒編",
				"ch2585610" => "咲-Saki-全国編",
				"ch2585573" => "未確認で進行形",
				//2014/spring
				"ch2591203" => "ご注文はうさぎですか？",
				"ch2590854" => "シドニアの騎士",
				"ch2590164" => "ジョジョの奇妙な冒険 スターダストクルセイダース",
				"ch2590206" => "蟲師　続章",
				"ch2590587" => "ラブライブ！2期",
				//2014/summer
				"ch2594935" => "アルドノア・ゼロ",
				"ch2595362" => "ソードアート・オンラインⅡ",
				"ch2595307" => "東京喰種",
				"ch2595036" => "幕末Rock",
				"ch2595174" => "ハナヤマタ",
				"ch2595987" => "ばらかもん",
				"ch2594336" => "美少女戦士セーラームーンCrystal",
				"ch2595271" => "プリパラ",
				"ch2591608" => "みならいディーバ",
				"ch2594241" => "ヤマノススメ セカンドシーズン",
				//2014/autumn
				"ch2599570" => "愛・天地無用！",
				"ch2598956" => "異能バトルは日常系のなかで",
				"ch2599558" => "オオカミ少女と黒王子",
				"ch2599996" => "ガールフレンド（仮）",
				"ch2599672" => "繰繰れ！コックリさん",
				"ch2599474" => "グリザイアの果実",
				"ch2599576" => "山賊の娘ローニャ",
				"ch2598859" => "神撃のバハムートGENESIS",
				"ch2599573" => "SHIROBAKO",
				"ch2599784" => "selector spread WIXOSS",
				"ch2599253" => "大図書館の羊飼い",
				"ch2590230" => "TERRAFORMARS",
				"ch2599088" => "トリニティセブン",
				"ch2599997" => "七つの大罪",
				"ch2598813" => "デンキ街の本屋さん",
				"ch2599443" => "なりヒロｗｗｗ",
				"ch2597633" => "Hi☆sCoool！ セハガール",
				"ch2598888" => "Fate/stay night",
				"ch2596677" => "Bonjour♪恋味パティスリー",
				"ch2599673" => "魔弾の王と戦姫",
				"ch2599128" => "弱虫ペダル GRANDE ROAD",
				"ch2599210" => "暁のヨナ",
				"ch2600168" => "甘城ブリリアントパーク",
				"ch2599572" => "失われた未来を求めて",
				"ch2600405" => "俺、ツインテールになります。",
				"ch2597475" => "オレん家のフロ事情",
				"ch2598938" => "怪盗ジョーカー",
				"ch2599694" => "寄生獣 セイの格率",
				"ch2599873" => "実在性ミリオンアーサー",
				"ch2598937" => "天体のメソッド",
				"ch2599169" => "旦那が何を言っているかわからない件",
				"ch2599995" => "曇天に笑う",
				"ch2590206" => "蟲師 続章",
				"ch2599669" => "結城友奈は勇者である",
				//2015/winter
				"ch2602856" => "アニメで分かる心療内科",
				"ch2602896" => "アブソリュート・デュオ",
				"ch2594935" => "アルドノア・ゼロ(2クール)",
				"ch2602980" => "幸腹グラフィティ",
				"ch2597821" => "キュートランスフォーマー 帰ってきたコンボイの謎  ",
				"ch2602662" => "銃皇無尽のファフニール",
				"ch2603188" => "ジョジョの奇妙な冒険 スターダストクルセイダース エジプト編",
				"ch2603114" => "聖剣使いの禁呪詠唱（ワールドブレイク）",
				"ch2603111" => "探偵歌劇 ミルキィホームズ TD",
				"ch2602905" => "デュラララ!!×２ 承",
				"ch2602578" => "ぱんきす！2次元",
				"ch2602658" => "美男高校地球防衛部LOVE！",
				"ch2602621" => "みりたり！",
				"ch2602700" => "みんな集まれ！ファルコム学園SC",
				"ch2603523" => "艦隊これくしょん -艦これ-",
				"ch2602382" => "アイドルマスターシンデレラガールズ",
				"ch2602586" => "神様はじめました◎",
				"ch2603143" => "戦国無双",
				"ch2603377" => "少年ハリウッド-HOLLY STAGE FOR 50-",
				"ch2594327" => "蒼穹のファフナー EXODUS",
				"ch2603109" => "デス・パレード",
				"ch2602540" => "東京喰種トーキョーグール√A",
				"ch2603277" => "ユリ熊嵐",
				"ch2602974" => "夜ノヤッターマン",
				"ch2602314" => "ローリング☆ガールズ",
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
