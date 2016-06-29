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
				"ch2606891" => "プリパラ　2nd season",
				"ch2611643" => "ウルトラスーパーアニメタイム",
				// 2015/autumn
				"ch2614611" => "おそ松さん",
				"ch2614174" => "ご注文はうさぎですか？？",
				// 2016/winter
				"ch2617580" => "おじさんとマシュマロ",
				"ch2617611" => "この素晴らしい世界に祝福を！",
				//2016/spring
				"ch2620298" => "うさかめ",
				"ch2614001" => "学戦都市アスタリスク 2nd Season",
				"ch2620384" => "キズナイーバー",
				"ch2620924" => "三者三葉",
				"ch2620827" => "はいふり",
				"ch2617474" => "薄桜鬼～御伽草子～",
				"ch2620187" => "ハンドレッド",
				"ch2620673" => "Re:ゼロから始める異世界生活",
				"ch2620883" => "アルティメット・スパイダーマン VS シニスター・シックス",
				"ch2620642" => "あんハピ♪",
				"ch2620164" => "宇宙パトロールルル子",
				"ch2620083" => "エンドライド",
				"ch2620289" => "鬼斬",
				"ch2619536" => "影鰐-KAGEWANI-承",
				"ch2620160" => "境界のRINNE 第2シリーズ",
				"ch2621300" => "クレーンゲール",
				"ch2620641" => "コンクリート・レボルティオ-超人幻想-THE LAST SONG",
				"ch2620675" => "くまみこ",
				"ch2620359" => "デュエル・マスターズ VSRF",
				"ch2620676" => "少年年アシベ GO！GO！ゴマちゃん",
				"ch2620540" => "少年メイド",
				"ch2620801" => "ジョジョの奇妙な冒険 ダイヤモンドは砕けない",
				"ch2621080" => "聖戦ケルベロス 竜刻のファタリテ",
				"ch2620421" => "双星の陰陽師",
				"ch2620680" => "ジョーカーゲーム",
				"ch2620803" => "テラフォーマーズ リベンジ",
				"ch2620679" => "ネトゲの嫁は女の子じゃないと思った？",
				"ch2620364" => "パンでPeace！",
				"ch2620865" => "秘密結社 鷹の爪 GT",
				"ch2620677" => "文豪ストレイドッグス",
				"ch2620795" => "僕のヒーローアカデミア",
				"ch2620678" => "ビッグオーダー",
				"ch2621123" => "ばくおん!!",
				"ch2606891" => "プリパラ 3rd season",
				"ch2620840" => "ふらいんぐうぃっち",
				"ch2620664" => "迷家-マヨイガ-",
				"ch2620539" => "ラグナストライクエンジェルズ",
				"ch2620640" => "ワガママハイスペック",
				//2016/summer
				"ch2623557" => "ラブライブ！サンシャイン!!",
				"ch2623562" => "テイルズ オブ ゼスティリア ザ クロス",
				"ch2618272" => "アクティヴレイド -機動強襲室第八係- 2nd",
				"ch2623136" => "あまんちゅ！",
				"ch2623590" => "この美術部には問題がある！",
				"ch2623598" => "D.Gray-man HALLOW",
				"ch2623420" => "TVアニメ「Rewrite」",
				"ch2623373" => "ばなにゃ",
				"ch2623734" => "美男高校地球防衛部LOVE！LOVE！",
				"ch2623597" => "B-PROJECT～鼓動＊アンビシャス～",
				"ch2623134" => "腐男子高校生活",
				"ch2623423" => "甘々と稲妻",
				"ch2623454" => "アンジュ・ヴィエルジュ",
				"ch2623372" => "OZMAFIA!!",
				"ch2623483" => "クオリディア・コード",
				"ch2623264" => "SERVAMP -サーヴァンプ-",
				"ch2623547" => "SHOW BY ROCK!!しょ～と!!",
				"ch2623551" => "スカーレッドライダーゼクス",
				"ch2623767" => "タイムトラベル少女～マリ・ワカと8人の科学者たち～",
				//"" => "タブー・タトゥー
				"ch2623563" => "チア男子!!",
				"ch2605854" => "トランスフォーマーアドベンチャー　-マイクロンの章-",
				"ch2623457" => "NEW GAME!",
				"ch2623766" => "初恋モンスター",
				"ch2623549" => "はんだくん",
				"ch2623456" => "Fate/kaleid liner プリズマ☆イリヤ ドライ!!",
				"ch2621033" => ",planetarian",
				"ch2623548" => "ベルセルク",
				"ch2623455" => "魔装学園H×H",
				"ch2623249" => "ReLIFE",
				"ch2623453" => "レガリア The Three Sacred Stars",
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
