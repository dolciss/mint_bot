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
				"ch60250" => "咲-Saki-阿知賀編 episode of side-A",
				//2013/01
				"ch2526034" => "ラブライブ！",
				//2013/04
				"ch2568065" => "プリティーリズム・レインボーライブ",
				//2013/summer
				"ch2575664" => "<物語>シリーズセカンドシーズン",
				//2013/autumn
				"ch2580588" => "キルラキル KILL la KILL",
				"ch2581383" => "ゴールデンタイム",
				"ch2576710" => "てさぐれ！部活もの",
				"ch2581937" => "東京レイヴンズ",
				"ch2581384" => "凪のあすから",
				//2014/winter
				"ch2581668" => "うーさーのその日暮らし 覚醒編",
				"ch2585704" => "ウィッチクラフトワークス",
				"ch2585574" => "Wake Up, Girls！",
				"ch2584917" => "お姉ちゃんが来た",
				"ch2585707" => "GO！GO！575",
				"ch2585336" => "最近、妹のようすがちょっとおかしいんだが。",
				"ch2585610" => "咲-Saki-全国編",
				"ch2585935" => "桜Trick",
				"ch2585576" => "世界征服～謀略のズヴィズダー～ ",
				"ch2585289" => "そにアニ　-SUPER SONICO THE ANIMATION-",
				"ch2585064" => "ストレンジ・プラス",
				"ch2585963" => "生徒会役員共＊",
				"ch2585815" => "Z/X IGNITION",
				//"chxxxxx" => "中二病でも恋がしたい!戀",
				"ch2585304" => "ディーふらぐ！",
				"ch2585251" => "とある飛空士への恋歌",
				"ch2583388" => "となりの関くん",
				"ch2585575" => "ニセコイ",
				"ch2577243" => "のうりん",
				"ch2585524" => "ノラガミ",
				"ch2585306" => "ノブナガ・ザ・フール",
				"ch2585063" => "ノブナガン",
				"ch2585705" => "ハマトラ",
				"ch2581671" => "pupaチャンネル",
				"ch2585820" => "鬼灯の冷徹",
				"ch2585303" => "魔法戦争",
				"ch2585573" => "未確認で進行形",
				"ch2582363" => "ロボットガールズZ",
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
