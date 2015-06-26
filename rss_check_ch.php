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
				//2014/winter
				"ch2581668" => "うーさーのその日暮らし 覚醒編",
				"ch2585610" => "咲-Saki-全国編",
				"ch2585573" => "未確認で進行形",
				//2014/spring
				"ch2591203" => "ご注文はうさぎですか？",
				"ch2590854" => "シドニアの騎士",
				"ch2590164" => "ジョジョの奇妙な冒険 スターダストクルセイダース",
				"ch2590587" => "ラブライブ！2期",
				//2014/summer
				"ch2595174" => "ハナヤマタ",
				"ch2594336" => "美少女戦士セーラームーンCrystal",
				"ch2595271" => "プリパラ",
				//2014/autumn
				"ch2599672" => "繰繰れ！コックリさん",
				"ch2599669" => "結城友奈は勇者である",
				//2015/winter
				"ch2602856" => "アニメで分かる心療内科",
				"ch2602980" => "幸腹グラフィティ",
				"ch2603188" => "ジョジョの奇妙な冒険 スターダストクルセイダース エジプト編",
				"ch2603013" => "みりたり！",
				"ch2603523" => "艦隊これくしょん -艦これ-",
				"ch2602382" => "アイドルマスターシンデレラガールズ",
				"ch2602586" => "神様はじめました◎",
				"ch2603143" => "戦国無双",
				"ch2603109" => "デス・パレード",
				//2015/spring
				"ch2607478" => "アルティメット・スパイダーマン ウェブ・ウォーリアーズ",
				"ch2606196" => "浦和の調ちゃん",
				"ch2607373" => "うたの☆プリンスさまっ♪ マジLOVEレボリューションズ",
				"ch2607080" => "おまかせ！みらくるキャット団",
				"ch2607394" => "俺物語!!",
				"ch2607420" => "終わりのセラフ",
				"ch2607265" => "ガンスリンガー ストラトス -THE ANIMATION-",
				"ch2607480" => "銀魂゜",
				"ch2605725" => "境界のRINNE",
				"ch2607418" => "グリザイアの迷宮",
				"ch2607419" => "グリザイアの楽園",
				"ch2607110" => "SHOW BY ROCK!!",
				"ch2606743" => "ダンジョンに出会いを求めるのは間違っているだろうか",
				"ch2606762" => "旦那が何を言っているかわからない件 2スレ目",
				"ch2606623" => "高宮なすのです！",
				"ch2606543" => "てーきゅう4期",
				"ch2606006" => "デュエル・マスターズ VSR",
				"ch2607266" => "ニセコイ：",
				"ch2576719" => "にゅるにゅる!!KAKUSENくん 2期",
				"ch2606797" => "ニンジャスレイヤー フロムアニメイシヨン",
				"ch2607260" => "ハロー!!きんいろモザイク",
				"ch2606941" => "響け！ユーフォニアム",
				"ch2598888" => "Fate/stay night -Unlimited Blade Works- 2ndシーズン",
				"ch2607264" => "プラスティック・メモリーズ",
				"ch2606448" => "ベイビーステップ 第2シリーズ",
				"ch2607261" => "ミカグラ学園組曲",
				"ch2607162" => "秘密結社 鷹の爪 DO（ドゥー）",
				"ch2602278" => "えとたま -干支魂-",
				"ch2606159" => "血界戦線",
				"ch2606890" => "シドニアの騎士 第九惑星戦役",
				"ch2576710" => "てさぐれ！部活もの すぴんおふ プルプルんシャルムと遊ぼう",
				"ch2605854" => "トランスフォーマー アドベンチャー",
				"ch2607481" => "トリアージX",
				"ch2607395" => "長門有希ちゃんの消失",
				"ch2607257" => "ハイスクールD×D BorN",
				"ch2607482" => "ふなっしーのふなふなふな日和",
				"ch2606891" => "プリパラ　2nd season",
				"ch2606855" => "放課後のプレアデス",
				"ch2607166" => "レーカン！",
				"ch2610846" => "青春×機関銃",
				"ch2610897" => "VENUS PROJECT",
				"ch2610838" => "うーさーのその日暮らし 夢幻編",
				"ch2610527" => "おくさまが生徒会長！",
				"ch2610989" => "がっこうぐらし!",
				"ch2610688" => "GATE（ゲート）自衛隊 彼の地にて、斯く戦えり",
				"ch2610950" => "ケイオスドラゴン～赤竜戦役～",
				"ch2610619" => "TVアニメ「Charlotte(シャーロット)」",
				"ch2610570" => "洲崎西THE ANIMATION",
				"ch2610809" => "戦姫絶唱シンフォギアGX",
				"ch2610953" => "デュラララ!!×２ 転",
				"ch2610526" => "だんちがい",
				"ch2611134" => "To LOVEる－とらぶる－ダークネス2nd",
				"ch2610948" => "干物妹！うまるちゃん",
				"ch2610003" => "監獄学園〈プリズンスクール〉",
				"ch2610952" => "モンスター娘のいる日常",
				"ch2610901" => "ビキニ・ウォリアーズ",
				"ch2610378" => "六花の勇者",
				"ch2610908" => "赤髪の白雪姫",
				"ch2610889" => "アクエリオンロゴス",
				"ch2610890" => "オーバーロード",
				"ch2610562" => "ガッチャマン クラウズ インサイト",
				"ch2610981" => "GANGSTA.",
				"ch2610960" => "空戦魔導士候補生の教官",
				"ch2611138" => "Classroom☆Crisis",
				"ch2610947" => "GOD EATER（ゴッドイーター）",
				"ch2610806" => "実は私は",
				"ch2610939" => "城下町のダンデライオン",
				"ch2610909" => "それが声優！",
				"ch2610365" => "てーきゅう 5期",
				"ch2610840" => "のんのんびより りぴーと",
				"ch2610959" => "Fate/kaleid liner プリズマ☆イリヤ ツヴァイ ヘルツ!",
				"ch2610938" => "ヘタリア The World Twinkle",
				"ch2609784" => "枕男子",
				"ch2610943" => "ミス・モノクローム-The Animation- 2",
				"ch2608685" => "ミリオンドール",
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
