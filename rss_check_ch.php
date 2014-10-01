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
				//2013/04
				"ch2568065" => "プリティーリズム・レインボーライブ",
				//2013/summer
				"ch2575664" => "<物語>シリーズセカンドシーズン",
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
				"ch2591055" => "あいまいみー～妄想カタストロフ～",
				"ch2595651" => "アオハライド",
				"ch2595567" => "アカメが斬る！",
				"ch2594935" => "アルドノア・ゼロ",
				"ch2595351" => "グラスリップ",
				"ch2595683" => "黒執事Book of Circus",
				"ch2594945" => "月刊少女 野崎くん",
				"ch2595346" => "さばげぶっ！",
				"ch2595414" => "少年ハリウッド",
				"ch2595391" => "白銀の意思　アルジェヴォルン",
				"ch2594859" => "人生相談テレビアニメーション「人生」",
				"ch2594980" => "真 ストレンジ・プラス",
				"ch2587524" => "スペース☆ダンディ",
				"ch2594944" => "精霊使いの剣舞",
				"ch2595988" => "戦国BASARA Judge End",
				"ch2595362" => "ソードアート・オンラインⅡ",
				"ch2595307" => "東京喰種",
				"ch2595176" => "ドラマティカルマーダー",
				"ch2595036" => "幕末Rock",
				"ch2595174" => "ハナヤマタ",
				"ch2595987" => "ばらかもん",
				"ch2594336" => "美少女戦士セーラームーンCrystal",
				"ch2593401" => "ひめゴト",
				"ch2595298" => "普通の女子高生が【ろこどる】やってみた。",
				"ch2596264" => "フランチェスカ",
				"ch2594938" => "TVアニメ『Free!-Eternal Summer-』",
				"ch2595271" => "プリパラ",
				"ch2594936" => "TVアニメ「ペルソナ4 ザ・ゴールデン」",
				"ch2594939" => "まじもじるるも",
				"ch2595858" => "毎度！浦安鉄筋家族",
				"ch2591608" => "みならいディーバ",
				"ch2594979" => "モモキュンソード",
				"ch2594241" => "ヤマノススメ セカンドシーズン",
				"ch2595297" => "RAIL WARS!",
				"ch2595224" => "リプライ ハマトラ",
				"ch2595352" => "六畳間の侵略者!?",
				//2014/autumn
				"ch2599570" => "愛・天地無用！",
				"ch2598956" => "異能バトルは日常系のなかで",
				"ch2599558" => "オオカミ少女と黒王子",
				"ch2599672" => "繰繰れ！コックリさん",
				"ch2599474" => "グリザイアの果実",
				"ch2598859" => "神撃のバハムートGENESIS",
				"ch2599573" => "SHIROBAKO",
				"ch2599784" => "selector spread WIXOSS",
				"ch2599253" => "大図書館の羊飼い",
				"ch2590230" => "TERRAFORMARS",
				"ch2599088" => "トリニティセブン",
				"ch2598813" => "デンキ街の本屋さん",
				"ch2597633" => "Hi☆sCoool！ セハガール",
				"ch2598888" => "Fate/stay night",
				"ch2596677" => "Bonjour♪恋味パティスリー",
				"ch2599673" => "魔弾の王と戦姫",
				"ch2599128" => "弱虫ペダル GRANDE ROAD",
				"ch2599210" => "暁のヨナ",
				"ch2599572" => "失われた未来を求めて",
				"ch2597475" => "オレん家のフロ事情",
				"ch2598938" => "怪盗ジョーカー",
				"ch2599694" => "寄生獣 セイの格率",
				"ch2599873" => "実在性ミリオンアーサー",
				"ch2598937" => "天体のメソッド",
				"ch2599169" => "旦那が何を言っているかわからない件",
				"ch2599995" => "曇天に笑う",
				"ch2590206" => "蟲師 続章",
				"ch2599669" => "結城友奈は勇者である",
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
