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
				"ch2590854" => "シドニアの騎士",
				"ch2590587" => "ラブライブ！2期",
				//2014/summer
				"ch2595174" => "ハナヤマタ",
				"ch2594336" => "美少女戦士セーラームーンCrystal",
				"ch2595271" => "プリパラ",
				//2014/autumn
				"ch2599672" => "繰繰れ！コックリさん",
				"ch2599669" => "結城友奈は勇者である",
				//2015/winter
				"ch2602382" => "アイドルマスターシンデレラガールズ",
				//2015/spring
				"ch2607110" => "SHOW BY ROCK!!",
				"ch2606797" => "ニンジャスレイヤー フロムアニメイシヨン",
				"ch2607260" => "ハロー!!きんいろモザイク",
				"ch2602278" => "えとたま -干支魂-",
				"ch2606159" => "血界戦線",
				"ch2606890" => "シドニアの騎士 第九惑星戦役",
				"ch2576710" => "てさぐれ！部活もの すぴんおふ プルプルんシャルムと遊ぼう",
				"ch2606891" => "プリパラ　2nd season",
				"ch2610846" => "青春×機関銃",
				"ch2610897" => "VENUS PROJECT",
				"ch2610838" => "うーさーのその日暮らし 夢幻編",
				"ch2610688" => "GATE（ゲート）自衛隊 彼の地にて、斯く戦えり",
				"ch2610950" => "ケイオスドラゴン～赤竜戦役～",
				"ch2611134" => "To LOVEる－とらぶる－ダークネス2nd",
				"ch2610948" => "干物妹！うまるちゃん",
				"ch2610003" => "監獄学園〈プリズンスクール〉",
				"ch2610952" => "モンスター娘のいる日常",
				"ch2610901" => "ビキニ・ウォリアーズ",
				"ch2610378" => "六花の勇者",
				"ch2611198" => "WORKING!!!",
				"ch2610908" => "赤髪の白雪姫",
				"ch2610889" => "アクエリオンロゴス",
				"ch2611643" => "ウルトラスーパーアニメタイム",
				"ch2610890" => "オーバーロード",
				"ch2610562" => "ガッチャマン クラウズ インサイト",
				"ch2610981" => "GANGSTA.",
				"ch2610960" => "空戦魔導士候補生の教官",
				"ch2611138" => "Classroom☆Crisis",
				"ch2610947" => "GOD EATER（ゴッドイーター）",
				"ch2610806" => "実は私は",
				"ch2610942" => "下ネタという概念が存在しない退屈な世界",
				"ch2610939" => "城下町のダンデライオン",
				"ch2610909" => "それが声優！",
				"ch2610365" => "てーきゅう 5期",
				"ch2610840" => "のんのんびより りぴーと",
				"ch2610959" => "Fate/kaleid liner プリズマ☆イリヤ ツヴァイ ヘルツ!",
				"ch2610938" => "ヘタリア The World Twinkle",
				"ch2609784" => "枕男子",
				"ch2610943" => "ミス・モノクローム-The Animation- 2",
				"ch2608685" => "ミリオンドール",
				"ch2611160" => "ワカコ酒",
				"ch2611424" => "わかば＊ガール",
				// 2015/autumn
				"ch2612507" => "ワンパンマン",
				"ch2607776" => "Dance with Devils",
				"ch2613787" => "DIABOLIK LOVERS MORE,BLOOD",
				"ch2614617" => "雨色ココア Rainy colorへようこそ！",
				"ch2614386" => "あにトレ！EX",
				"ch2614387" => "ヴァルキリードライヴ マーメイド",
				"ch2614839" => "うたわれるもの　偽りの仮面",
				"ch2614008" => "俺がお嬢様学校に「庶民サンプル」としてゲッツされた件",
				"ch2607420" => "終わりのセラフ 第２クール",
				"ch2614318" => "終物語",
				"ch2614001" => "学戦都市アスタリスク",
				"ch2614376" => "影鰐-KAGEWANI-",
				"ch2614373" => "かみさまみならい ヒミツのここたま",
				"ch2614378" => "血液型くん！3期",
				"ch2614801" => "小森さんは断れない！",
				"ch2614149" => "進撃！巨人中学校",
				"ch2614086" => "スタミュ",
				"ch2614385" => "てーきゅう　6期",
				"ch2614379" => "ハッカドール THE あにめ〜しょん",
				"ch2614002" => "不思議なソメラちゃん",
				"ch2614747" => "ヘヴィーオブジェクト",
				"ch2614648" => "DD北斗の拳2　イチゴ味＋",
				"ch2614177" => "ミス・モノクローム-The Animation- 3",
				"ch2614011" => "ヤング ブラック・ジャック",
				"ch2614638" => "ゆるゆり さん☆ハイ！",
				"ch2611643" => "ウルトラスーパーアニメタイム",
				"ch2614611" => "おそ松さん",
				"ch2613786" => "温泉幼精ハコネちゃん",
				"ch2614382" => "牙狼 -紅蓮ノ月-",
				"ch2614418" => "金田一少年の事件簿R（2015）",
				"ch2614178" => "K RETURN OF KINGS",
				"ch2614174" => "ご注文はうさぎですか？？",
				"ch2614389" => "スター・ウォーズ 反乱者たち",
				"ch2594327" => "蒼穹のファフナー EXODUS（第二クール）",
				"ch2614390" => "ちいさなプリンセス ソフィア",
				"ch2605854" => "トランスフォーマー アドベンチャー",
				"ch2614347" => "ノラガミ ARAGOTO",
				"ch2614604" => "ハイキュー!! セカンドシーズン",
				"ch2614639" => "Peeping Life TV シーズン1 ??",
				"ch2614753" => "緋弾のアリアAA",
				"ch2613729" => "落第騎士の英雄譚（キャバルリィ）",
				"ch2614640" => "ランス・アンド・マスクス",
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
