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
				"ch2612507" => "ワンパンマン",
				"ch2607776" => "Dance with Devils",
				"ch2613787" => "DIABOLIK LOVERS MORE,BLOOD",
				"ch2614617" => "雨色ココア Rainy colorへようこそ！",
				"ch2614386" => "あにトレ！EX",
				"ch2614797" => "あにトレ！EX",
				"ch2614387" => "ヴァルキリードライヴ マーメイド",
				"ch2614280" => "うたわれるもの 偽りの仮面",
				"ch2614839" => "うたわれるもの 偽りの仮面",
				"ch2614008" => "俺がお嬢様学校に「庶民サンプル」としてゲッツされた件",
				"ch2607420" => "終わりのセラフ 第２クール",
				"ch2614318" => "終物語",
				"ch2614001" => "学戦都市アスタリスク",
				"ch2614376" => "影鰐-KAGEWANI-",
				"ch2614373" => "かみさまみならい ヒミツのここたま",
				"ch2614378" => "血液型くん！3期",
				"ch2614799" => "血液型くん！3期",
				"ch2614801" => "小森さんは断れない！",
				"ch2614149" => "進撃！巨人中学校",
				"ch2614086" => "スタミュ",
				"ch2614385" => "てーきゅう　6期",
				"ch2614800" => "てーきゅう　6期",
				"ch2614379" => "ハッカドール THE あにめ〜しょん",
				"ch2614002" => "不思議なソメラちゃん",
				"ch2614747" => "ヘヴィーオブジェクト",
				"ch2614648" => "DD北斗の拳2　イチゴ味＋",
				"ch2614177" => "ミス・モノクローム-The Animation- 3",
				"ch2614638" => "ゆるゆり さん☆ハイ！",
				"ch2611643" => "ウルトラスーパーアニメタイム（第2クール）",
				"ch2614611" => "おそ松さん",
				"ch2613786" => "温泉幼精ハコネちゃん",
				"ch2614382" => "牙狼 -紅蓮ノ月-",
				"ch2614418" => "金田一少年の事件簿R（2015）",
				"ch2614178" => "K RETURN OF KINGS",
				"ch2614174" => "ご注文はうさぎですか？？",
				"ch2614841" => "櫻子さんの足下には死体が埋まっている",
				"ch2614882" => "新妹魔王の契約者 BURST",
				"ch2614389" => "スター・ウォーズ 反乱者たち",
				"ch2594327" => "蒼穹のファフナー EXODUS（第2クール）",
				"ch2614881" => "対魔導学園35試験小隊",
				"ch2614390" => "ちいさなプリンセス ソフィア",
				"ch2605854" => "トランスフォーマーアドベンチャー",
				"ch2614347" => "ノラガミ ARAGOTO",
				"ch2614604" => "ハイキュー!! セカンドシーズン",
				"ch2614639" => "Peeping Life TV シーズン1 ??",
				"ch2614753" => "緋弾のアリアAA",
				"ch2614011" => "ヤング ブラック・ジャック",
				"ch2613729" => "落第騎士の英雄譚（キャバルリィ）",
				"ch2614640" => "ランス・アンド・マスクス",
				// 2016/winter
				"ch2617580" => "おじさんとマシュマロ",
				"ch2617720" => "シュヴァルツェスマーケン",
				"ch2617613" => "デュラララ!!×2 結",
				"ch2617616" => "プリンス・オブ・ストライド オルタナティブ",
				"ch2617714" => "無彩限のファントム・ワールド",
				"ch2617552" => "蒼の彼方のフォーリズム",
				"ch2617554" => "赤髪の白雪姫 2ndシーズン",
				"ch2617581" => "大家さんは思春期！",
				"ch2617618" => "おしえて！ ギャル子ちゃん",
				"ch2617834" => "血液型くん！4期",
				"ch2617614" => "紅殻のパンドラ",
				"ch2617611" => "この素晴らしい世界に祝福を！",
				"ch2617321" => "最弱無敗の神装機竜《バハムート》",
				"ch2617587" => "少女たちは荒野を目指す",
				"ch2617094" => "SUSHI POLICE",
				"ch2617617" => "石膏ボーイズ",
				"ch2597821" => "生誕20周年ビーストウォーズ復活祭への道",
				"ch2618231" => "旅街レイトショー",
				"ch2617491" => "ディバインゲート",
				"ch2617816" => "てーきゅう 7期",
				"ch2617594" => "Dimension W [ディメンション  ダブリュー]",
				"ch2617132" => "ナースウィッチ小麦ちゃんＲ",
				"ch2617553" => "ノルン+ノネット",
				"ch2617493" => "灰と幻想のグリムガル",
				"ch2617612" => "ハルチカ-ハルタとチカは青春する-",
				"ch2617313" => "ファンタシースターオンライン2 ジ アニメーション",
				"ch2617615" => "ブブキ・ブランキ",
				"ch2617860" => "魔法少女なんてもういいですから。",
				"ch2617482" => "闇芝居 三期",
				"ch2617352" => "霊剣山　星屑たちの宴",
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
