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
				"ch2590140" => "悪魔のリドル",
				"ch2590942" => "一週間フレンズ。",
				"ch2589898" => "犬神さんと猫山さん",
				"ch2590218" => "エスカ&ロジーのアトリエ〜黄昏の空の錬金術士〜",
				"ch2590502" => "M３〜ソノ黒キ鋼〜",
				"ch2590550" => "オレカバトル",
				"ch2590084" => "カードファイト!! ヴァンガード　レギオンメイト編",
				"ch2590142" => "彼女がフラグをおられたら",
				"ch2590262" => "神々の悪戯",
				"ch2590980" => "超爆裂異次元メンコバトル ギガントシューター つかさ",
				"ch2590584" => "金色のコルダ Blue♪Sky",
				"ch2590222" => "健全ロボ ダイミダラー",
				"ch2590541" => "極黒のブリュンヒルデ",
				"ch2591203" => "ご注文はうさぎですか？",
				"ch2590854" => "シドニアの騎士",
				"ch2590164" => "ジョジョの奇妙な冒険 スターダストクルセイダース",
				"ch2590227" => "星刻の竜騎士",
				"ch2590259" => "selector infected WIXOSS",
				"ch2590225" => "ソウルイーターノット！",
				"ch2590856" => "それでも世界は美しい",
				"ch2591033" => "一週間フレンズ。",
				"ch2590487" => "テンカイナイト",
				"ch2590551" => "ドラゴンコレクション",
				"ch2590219" => "ノーゲーム・ノーライフ",
				"ch2590815" => "秘密結社 鷹の爪 エクストリーム",
				"ch2590488" => "FAIRY TAIL",
				//"ch2590261" => "ぷちます！！‐プチプチ・アイドルマスター‐",
				"ch2590602" => "ブラック・ブレット",
				"ch2590264" => "ブレイドアンドソウル",
				"ch2590816" => "僕らはみんな河合荘",
				"ch2591053" => "マジンボーン",
				"ch2590586" => "魔法科高校の劣等生",
				"ch2590603" => "マンガ家さんとアシスタントさんと",
				"ch2590206" => "蟲師　続章",
				"ch2587663" => "メカクシティアクターズ",
				"ch2590260" => "召しませロードス島戦記～それっておいしいの？～",
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
				"ch2595362" => "ソードアート・オンラインⅡ",
				"ch2595307" => "東京喰種",
				"ch2595176" => "ドラマティカルマーダー",
				"ch2595036" => "幕末Rock",
				"ch2595174" => "ハナヤマタ",
				"ch2594336" => "美少女戦士セーラームーンCrystal",
				"ch2593401" => "ひめゴト",
				"ch2595298" => "普通の女子高生が【ろこどる】やってみた。",
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
