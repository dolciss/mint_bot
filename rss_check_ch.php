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
				"ch60250" => "咲-Saki-阿知賀編 episode of side-A",
				//2013/01
				"ch2526034" => "ラブライブ！",
				//2013/04
				"ch2565318" => "銀河機攻隊 マジェスティックプリンス",
				"ch2565327" => "進撃の巨人",
				"ch2567097" => "とある科学の超電磁砲S",
				"ch2568065" => "プリティーリズム・レインボーライブ",
				//2013/summer
				"ch2575233" => "犬とハサミは使いよう",
				"ch2576245" => "神さまのいない日曜日",
				"ch2576246" => "神のみぞ知るセカイ　女神篇",
				"ch2576110" => "君のいる町",
				"ch2576574" => "きんいろモザイク",
				"ch2575731" => "劇場版｢空の境界｣",
				"ch2575662" => "幻影ヲ駆ケル太陽",
				"ch2576111" => "げんしけん二代目",
				"ch2575665" => "サーバント×サービス",
				"ch2574251" => "ステラ女学院高等科C3部",
				"ch2575849" => "戦姫絶唱シンフォギアＧ",
				"ch60415" => "戦勇。",
				"ch2575837" => "たまゆら -もあぐれっしぶ-",
				"ch2576374" => "ダンガンロンパ　Ｔｈｅ　Ａｎｉｍａｔｉｏｎ",
				"ch2575898" => "超次元ゲイム ネプテューヌ",
				"ch2576296" => "てーきゅう2期",
				"ch2576576" => "ハイスクールD×D NEW",
				"ch2576643" => "ファンタジスタドール",
				"ch2576363" => "ふたりはミルキィホームズ",
				"ch2576572" => "BROTHERS CONFLICT(ブラザーズ コンフリクト)",
				"ch2576247" => "Free！",
				"ch2576314" => "Fate/kaleid liner プリズマ☆イリヤ",
				"ch2575664" => "<物語>シリーズセカンドシーズン",
				"ch2576248" => "魔界王子 devils and realist",
				"ch2574861" => "リコーダーとランドセル　ミ☆",
				"ch2575651" => "恋愛ラボ",
				"ch2576244" => "ロウきゅーぶ！SS",
				"ch2574860" => "ローゼンメイデン",
				"ch2576575" => "私がモテないのはどう考えてもお前らが悪い！",
				//2013/autumn
				"ch2582279" => "アウトブレイク・カンパニー",
				"ch2580055" => "蒼き鋼のアルペジオ -アルス・ノヴァ-",
				"ch2582002" => "IS＜インフィニット・ストラトス＞2",
				"ch2567081" => "革命機ヴァルヴレイヴ",
				"ch2581768" => "境界の彼方",
				"ch2581936" => "京騒戯画",
				"ch2581999" => "ぎんぎつね",
				"ch2580588" => "キルラキル KILL la KILL",
				"ch2581383" => "ゴールデンタイム",
				"ch2581385" => "ストライク・ザ・ブラッド",
				"ch2582360" => "声優戦隊ボイストーム7",
				"ch2579855" => "せかつよチャンネル",
				"ch2582357" => "ダイヤのA",
				"ch2581926" => "てーきゅう３期チャンネル",
				"ch2580972" => "DIABOLIK LOVERS",
				"ch2576710" => "てさぐれ！部活もの",
				"ch2581937" => "東京レイヴンズ",
				"ch2581384" => "凪のあすから",
				"ch2581583" => "のんのんびより",
				"ch2581671" => "pupaチャンネル",
				"ch2581660" => "BLAZBLUE ALTER MEMORY",
				"ch2581585" => "フリージング ヴァイブレーション",
				"ch2580590" => "WHITE ALBUM2",
				"ch2581586" => "機巧少女は傷つかない",
				"ch2581984" => "ミス・モノクローム",
				"ch2580973" => "メガネブ！",
				"ch2580921" => "夜桜四重奏-ハナノウタ-",
				"ch2581285" => "弱虫ペダル",
				"ch2581661" => "リトルバスターズ！-Refrain-",
				"ch2582005" => "ワルキューレロマンツェ",
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
