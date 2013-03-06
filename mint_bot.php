<?php
// mint_bot.php
// はっかうさぎ

require_once("Twitter.php");

user_stream($mint_bot_m, array("replies"=>"all"), "mint_stream");
status_update($mint_bot_m, "@L_tan UserStream切れた～(´･ω･`)");
exit();

function mint_stream($data)
{
	static $following = array();
	
	$json = json_decode(strstr($data, "{"));
	if(isset($json->text)){
		print(date("Y/m/d H:i:s", strtotime($json->created_at)).":");
		print($json->user->name."(@".$json->user->screen_name.")");
		print(" via ".strip_tags($json->source).PHP_EOL);
		print($json->text.PHP_EOL."==".$json->user->statuses_count."==> ");
		if(!in_array($json->user->id, $following)){
			print("Not Following.".PHP_EOL);
		} else {
			print(kiri_count($json)." ");
			print(reply($json).PHP_EOL);
		}
	} else if(isset($json->event)){
		print("Event:".$json->event.PHP_EOL);
		if($json->event == "follow"){
			print($json->source->screen_name." -> ");
			print($json->target->screen_name);
			if($json->source->screen_name == "Mint_bot"){
				$following[] = $json->target->id;
				print(" Added.");
			}
			print(PHP_EOL);
		}
	} else if(isset($json->friends)){
		$following = $json->friends;
		$following[] = 101409775; // Mint_bot
		print("Friends:".count($json->friends).PHP_EOL);
	} else if(isset($json->disconnect)){
		print("Disconnect Stream:".$json->disconnect->code);
		print("(".$json->disconnect->stream_name.")");
		print(" => ".$json->disconnect->reason.PHP_EOL);
	} else {
		print("Unprocess Json.".PHP_EOL);
		print_r($json);
	}
	/*
	if(($pid = pcntl_wait($stat, WNOHANG)) > 0){
		print("Child Process (".$pid.") Dead.".PHP_EOL);
	}
	*/
}

// キリ番お知らせ
function kiri_count($json)
{
	global $mint_bot_m;
	$screen_name = $json->user->screen_name;
	$statuses_count = $json->user->statuses_count;
	$reply_id = $json->id;
	
	$check_count = strval($statuses_count+1);
	if(preg_match("/^(([1-9]0{3,})|(([1-9])\\4{3,})|([1-9]*0{4,}))$/u",
					$check_count) !== 1){
		return "Not Kiriban Tweet.";
	}

	/*
	// multi-process
	$pid = pcntl_fork();
	if($pid == -1){
		return "Kiriban Tweet. Fork Failed...";
	} else if($pid){
		// parent process
		return "Kiriban Tweet.".PHP_EOL."Parent Process Fork(".$pid.") Loop.";
	}
	// child process (return禁止!)
	print("Child Process (".getmypid().") Start.".PHP_EOL);
	*/

	if($screen_name == "Mint_bot"){
		$message = "これで".$check_count."ツイートみたい、これからもがんばります♪";
		tweet_message($mint_bot_m, $message, null, 0);
	} else {
		$message  = "@".$screen_name." さん、";
		$message .= "次が".$check_count."ツイートみたいです、お知らせ～";
		tweet_message($mint_bot_m, $message, $reply_id, 0);
	}

	//print("Child Process (".getmypid().") End.".PHP_EOL);
	//exit();
}

// 返事
function reply($json)
{
	global $mint_bot_m;
	$text = $json->text;
	$screen_name = $json->user->screen_name;
	$reply_id = $json->id;

	if(strpos($text, "RT") !== false){
		// RTがあったら反応しない
		return "ReTweet.";
	}
	if(strpos($text, "QT") !== false){
		// QTがあっても反応しない
		return "QuoteTweet.";
	}
	if(strpos($screen_name, "Mint_bot") !== false){
		// 自分の発言には反応しない
		return "SelfTweet.";
	}
	// bot発言も反応しない
	$botter = array("twiroboJP", "twittbot.net");
	foreach($botter as $b){
		if(strpos($json->source, $b) !== false){
			return "Bot Tweet.";
		}
	}

	// リプライもらった発言を消す
	if((strpos($text, "@Mint_bot バルス") !== false)
		&&($screen_name == "L_tan")){
		statuses_destroy($mint_bot_m, $json->in_reply_to_status_id);
	}

	// とりあえず自分宛を省いて、他にも@があったら反応しない
	$text = str_replace("@Mint_bot", "", $text, $count);
	if(strpos($text, "@") !== false) {
		return "OtherMentionTweet.";
	}

	if($count > 0){
		// リプライあり
		list($message, $wait) = mention($json);
	} else {
		// リプライなし
		list($message, $wait) = timeline($json);
	}
	if($message === false){
		return "No Hit Keyword...";
	}

	/*
	// multi-process
	$pid = pcntl_fork();
	if($pid == -1){
		return "Fork Failed...";
	} else if($pid){
		// parent process
		return "Parent Process Fork(".$pid.") Loop.";
	}
	// child process (return禁止!)
	print("Child Process (".getmypid().") Start.".PHP_EOL);
	*/
	tweet_message($mint_bot_m, $message, $reply_id, $wait);

	//print("Child Process (".getmypid().") End.".PHP_EOL);
	//exit();
}

// リプライあり
function mention($json)
{
	$message = "@".$json->user->screen_name." ";
	$name = trim_name($json->user->screen_name, $json->user->name);
	$text = $json->text;
	$wait = null;
	
	if(strpos($text, "!omikuji") !== false) {
		$message .= "っ【". get_fortune(). "】";
	} else if(strpos($text, "!mikunotter") !== false){
		$message .= mikunotter();
	} else if(preg_match("/(おすすめ|オススメ|)曲([をが]?(おすすめ|オススメ)|は.*？|を?教えて|を?おしえて)/u",
				$text) === 1){
		$message .= mikunotter();
	} else if((strpos($text, "おめでと") !== false)&&(date("md")=="0101")){
		$message .= "おめでとうございます！っ【". get_fortune(). "】";
	} else if((strpos($text, "おみくじ") !== false)&&(date("md")=="0101")){
		$message .= "っ【". get_fortune(). "】";
	} else if((strpos($text, "チョコ") !== false)&&(date("md")=="0214")){
		if((strpos($text, "くれ") !== false)
			||(strpos($text, "ちょうだ") !== false)
			||(strpos($text, "ちょーだ") !== false)
			||(strpos($text, "くだ") !== false)){
			switch(rand(0,8)){
				case 0: $message .= "・・・/// っ【チョコ】"; break;
				case 1: $message .= "・・・/// っ【チロル】"; break;
				case 2: $message .= "・・・/// っ【ブラックサンダー】"; break;
				case 3: $message .= "・・・/// っ【マーブルチョコ】"; break;
				case 4: $message .= "(ﾉ・∀・)ﾉ＝＝[蒟蒻]))ﾟДﾟ)･∵．"; break;
				default:$message .= "ほぇ？なにか言いました？"; break;
			}
		} else if(strpos($text, "？") === false){
			if((strpos($text, "あげる") !== false)
				||(strpos($text, "どうぞ") !== false)){
				$message .= "わ、わわっ、わわわーっ！///";
			} else if((strpos($text, "欲しい") !== false)
				||(strpos($text, "ほしい") !== false)){
				$message .= "あれ？こんなところに何か・・・っ【麦チョコ】";
			} else {
				$message .= "チョコレート・・・？";
			}
		} else {
			$message .= "甘いもの・・・じゅるる";
		}
	} else if(preg_match("/(ご苦労)|(お(疲|つか)れ)|(げんき|元気|[い生]きてる)(ですか|ー?？)/u",
				$text) === 1){
		switch(rand(0,2)){
			case 0:  $message .= "今日も元気にがんばってます～"; break;
			case 1:
				$message .= "うにゅ…はっ、ねてない、寝てないよ！"; break;
			default:
				$message .= "今日もおしごとおしごと♪"; break;
		}
	} else if(preg_match("/((あい|まい|愛)して[るう])|(([す好]き|らぶ)[やでっよだー！])/u", $text) === 1) {
		switch(rand(0,6)){
			case 0:  $message .= "あ、ありがとうございますっ♪"; break;
			case 1:  $message .= "わ、わたしも".$name."さんのこと…";
					 $message .= "　ってなにいってんのわたしっ///"; break;
			case 2:  $message .= "わーい！"; break;
			case 3:  $message .= "そ、そんな・・・///"; break;
			case 4:  $message .= "(*´ェ｀*)"; break;
			case 5:  $message .= "そんなこと言っちゃってもーｗ";break;
			default: $message .= "にゃー恥ずかしいセリフ禁止っ///"; break;
		}
	} else if(preg_match("/(かわ|可愛)[ゆいぃ]*[ぃいおよすっね!！…]/u", $text) === 1){
		switch(rand(0,6)){
			case 0:  $message .= "あ、ありがとう…///"; break;
			case 1:  $message .= "はわわっ、やめてーｗ"; break;
			case 2:  $message .= "またまたぁ、お世辞どうもっｗ"; break;
			case 3:  $message .= "きゃーっ、かわいいだなんて///"; break;
			case 4:  $message .= "(〃▽〃)"; break;
			case 5:  $message .= "き、きこえなーい///"; break;
			default: $message .= "そ、そんなことないもんっ"; break;
		}
	} else if(preg_match("/(策士|優秀|えらい|すごい)/u", $text) === 1){
		$message .= "えへへ、ありがとうございますっ";
	} else if(preg_match("/も(よろ|宜)しく/u", $text) === 1){
		$message .= "こちらこそですー";
	} else if(preg_match("/お(かえ|帰)り/u", $text) === 1){
		$message .= "ただいまです～";
	} else if(preg_match("/(ちゅっ|(はぁ|ハァ|ﾊｧ|はす|ハス|ﾊｽ|ぺろ|ペロ|ﾍﾟﾛ|pr|hs|ｐｒ|ｈｓ)+)/u", $text) === 1){
		switch(rand(0,4)){
			case 0:  $message .= "にゃはは///"; break;
			case 1:  $message .= "うぅー・・・（上目遣い"; break;
			case 2:  $message .= "(*'ω'*)......んゅ?"; break;
			case 3:  $message .= "もー、そういうのはですね・・・";
			         $message .= "（ごにょごにょ"; break;
			default: $message .= "ひゃうっ！？"; break;
		}
	} else if(preg_match("/(もふ|モフ|ﾓﾌ|つん|ふも|ぎゅっ|なで|ナデ|ﾅﾃﾞ|ふに|ぷに|プニ)+/u",
				$text, $match) === 1){
		switch(rand(0,6)){
			case 0: $message .= "はぅ・・・///"; break;
			case 1: $message .= "ちょ、ちょっとっ・・・えへへーｗ"; break;
			case 2: $message .= "こ、こらーっ！ｗ"; break;
			case 3: $message .= "あぅーっ！"; break;
			case 4: $message .= "(*'-')"; break;
			case 5: $message .= "ひあっ！？"; break;
			default:$message .= $match[1].$match[1]."っ！///"; break;
		}
	} else if(preg_match("/((もみ|もみっ|ぱふ|さわ|モミ|ﾓﾐ)+|ぎゅ)/u", $text) === 1){
		switch(rand(0,5)){
			case 0: $message .= "きゃっ！・・・あぅ///"; break;
			case 1: $message .= "・・・もーっｗ"; break;
			case 2: $message .= "なにやってるんですかーっ！ｗ"; break;
			case 3: $message .= "←・・・・・・(・ω・。) ｼﾞｰｯ"; break;
			case 4: $message .= "あっ、やっ、ちょっ・・・とっ・・・"; break;
			default:$message .= "え、えっちなのはいけないとおもいますっ";
					break;
		}
	} else if(preg_match("/揉[みむん]/u", $text) === 1){
		$message .= "にゃ、にゃーっ！///";
	} else if(preg_match("/[明あ]け(おめ|ましてお)/u", $text) === 1){
		$message .= "おめでとうございます！っ【". get_fortune(). "】";
	} else if(strpos($text, "バルス") !== false){
		$message .= "めっ、目がああぁぁ＞＜";
	} else if(strpos($text, "ありがと") !== false){
		$message .= "いえ～";
	} else if(strpos($text, "ありです") !== false){
		$message .= "いえ～";
	} else if(strpos($text, "ありでし") !== false){
		$message .= "いえ～";
	} else if(strpos($text, "大丈夫か？") !== false){
		switch(rand(0,2)){
			case 0:  $message .= "大丈夫です、問題ないでありますっ"; break;
			case 1:  $message .= "大丈夫・・・だと思うぅ・・・"; break;
			default: $message .= "問題だっ（ぇ"; break;
		}
	} else if(strpos($text, "おはよ") !== false){
		$message .= "はーいｗ";
	} else if(strpos($text, "ごちそうさま") !== false){
		$message .= "おそまつさまです～";
	} else if(strpos($text, "ごめん") !== false){
		$message .= "いえいえ…";
	} else if(strpos($text, "ちがう") !== false){
		$message .= "はうっ";
	} else if(strpos($text, "違う") !== false){
		$message .= "おうふ…";
	} else if(strpos($text, "うさぎ") !== false){
		$message .= "∩_∩＜ぴょこっ";
	} else if(strpos($text, "呼んだ") !== false){
		$message .= "いえいっw";
	} else if(strpos($text, "にゃい") !== false){
		$message .= "にゃい！";
	} else if(strpos($text, "///") !== false){
		$message .= "んもぅ・・・///";
	} else if(preg_match("/[ｗw]{3,}/u", $text) === 1){
		$message .= "わ、わらいすぎです…ｗ";
	} else if(($temp = keyword($json)) !== false){
		$message .= $temp;
	} else {
		switch(rand(0,5)){
			case 0:  $message .= "うーん？ｗ"; break;
			case 1:  $message .= "うふふｗ"; break;
			case 2:  $message .= "そうね・・・"; break;
			case 3:  $message .= "なーに？"; break;
			case 4:  $message .= "えっ？"; break;
			default: $message .= "( ･∀･)つ〃∩ ﾍｪｰ×".rand(0,99); break;
		}
	}
	return array($message, $wait);
}

// おみくじ
function get_fortune()
{
	$fortune = array(
				"大吉"     => 15,	"中吉"     => 20,	"吉"       => 20,
				"小吉"     => 15,	"末吉"     => 10,	"凶"       =>  5,
				"大凶"     =>  2,	"豚"       =>  5,	"ぴょん吉" =>  3,
				"だん吉"   =>  3,	"神"       =>  1,	"女神"     =>  1,
				);
	$fortune_list = array();
	$fortune_num = 0;
	foreach($fortune as $at => $num){
		for($i = 0; $i < $num; $i++){
			$fortune_list[] = $at;
		}
		$fortune_num += $num;
	}
	return $fortune_list[rand(0,$fortune_num-1)];
}

function mikunotter()
{
require("mikunopop.php");
	$select = $mikuno[rand(0,count($mikuno)-1)];
	$ret  = "さんにおすすめなミクノ曲は「".$select["title"]."」";
	$ret .= "(".$select["time"].")";
	$ret .= "かしら、彡".$select["hige"]."っ！ ";
	$ret .= "http://nico.ms/".$select["id"];
	return $ret;
}

// リプライなし
function timeline($json)
{
	$message = false;
	$wait = null;
	$screen_name = $json->user->screen_name;
	$text = $json->text;

	if(strpos($text, "ぬるぽ") !== false){
		switch(rand(0,2)){
			case 0: $message .= "ｶﾞｯ"; break;
			case 1: $message .= "■━⊂( ･∀･) 彡 ｶﾞｯ☆`Д´)ﾉ"; break;
			default:$message .= "■━⊂( ･∀･) 彡ｶﾞｯ☆( д)　　ﾟ　ﾟ"; break;
		}
		$wait = 0;
	} else if(yome($text)){
		switch(rand(0,2)){
			case 0:  $message .= "阻止っ！"; break;
			case 1:  $message .= "阻止・ω・)▄︻┻┳═一    =・　ﾊﾟｰﾝ!"; break;
			default: $message .= "阻止( ﾟДﾟ)ﾉ　三　　≓∰⋛⇋⇋⇋>"; break;
		}
		if(strpos($text, "はっか") !== false){
			$message .= "・・・ってええぇぇ！///";
		}
		$wait = 0;
	} else if((strpos($text, "よるほ") !== false)
				&&(date("His", strtotime($json->created_at)) == "000000")){
		$message .= "わっ、よるほーぴったりおめでとう♪";
	} else if(preg_match("/(むくり|ｍｋｒ|おはよう|オハヨ)/u", $text) === 1){
		if(rand(0,9)==0){
			$message .= "オハヨーハヨー♪  ";
		} else {
			switch($screen_name){
				case "shaghar":		case "wo_3":		case "akirakko":
				case "cashiwagi":	case "kurokoXXkuro":case "gingyoniku":
				case "gioca_":		case "3asyk":		case "neo_kuranchi":
				case "nico_agehaP":	case "kiko_0":		case "chikuwagirls":
				case "chikuwagirls_":	case "konami_1717":
					$message .= "おはようございますにゃん♪ ";
					break;
				case "aquan_39":
					$message .= "おはようございますにゃい♪ ";
					break;
				case "tsu9ne":
					if(strpos($text, "みぃこ") !== false){
						$message = false;
						break;
					}
					// through
				default:
					$message .= "おはようございます♪ ";
					break;
			}
		}
		$message .= "今日は".date("n/j(D)")."、";
		$message .= today_is(time());
		$message .= "いま".date("G:i")."です～";
		switch(date("G")){
			case "5":  case "6":  case "7":  case "8":
				$message .= "今日も1日がんばりましょう～"; break;
			case "9":  case "10": case "11": case "16":
			case "17": case "18": case "19": case "20":
				/*$message .= "いま".date("G:i")."ですよ～";*/ break;
			case "12": case "13": case "14": case "15":
				$message .= "もうお昼ですよ？ｗ"; break;
			case "21": case "22": case "23": case "0":
				$message .= "こんな時間に起きて・・・もぅ"; break;
			case "1":  case "2":  case "3":  case "4":
				$message .= "ずいぶん早起きですねｗ"; break;
			default:
				return array(false, null);
		}
	} else if(preg_match("/お(やす|休)み(なさー?[いく]|なしあ|っく)/u", $text) === 1){
		if($screen_name != "shaghar"){
			$message .= "おやすみなさい、良い夢を♪";
		} else {
			$message .= "おやすみなさいにゃん、良い夢を♪";
		}
	} else if(preg_match("/はっか(た[～ー]?ん|ちゃ[～ー]?ん|うさぎ)/u", $text) === 1){
		switch(rand(0,3)){
			case 0:  $message .= "お呼びでしょうかー？"; break;
			case 1:  $message .= "はいっ、はっかたんですよー"; break;
			case 2:  $message  = "|∀･) QT @".$screen_name.": ".$text; break;
			default: if(rand(0,47) == 0){
						$message .= "き、気安く呼ばないでくださいよねっ！？";
					 } else {
						$message .= "呼ばれた気がするっ";
					 }
					break;
		}
	} else {
		switch(date("md")){
			case "0401":
				if(strpos($text, "#エイプリルフール") !== false){
					switch(rand(0,3)){
						case 0:	$message = "ほんとっ！？"; break;
						case 1: $message = "えっ？"; break;
						case 2: $message = "またまたご冗談を・・・ｗ"; break;
						default:$message = "(￢д￢。) "; break;
					}
				} else if(strpos($text, "#嘘") !== false){
					$message = "嘘だっ！";
				} else {
					break;
				}
				$message .= " QT @".$screen_name.": ".$text;
				return array($message, 0);
				break;
			default: break;
		}
		$message = keyword($json);
	}

	if($message !== false){
		$message = "@".$screen_name." ".$message;
	}
	return array($message, $wait);
}

// 今日は何の日
function today_is($timestamp){
	$ret = "";
	switch(date("m/d", $timestamp)){
		case "01/01": $ret="元日です。あけましておめでとうございます！"; break;
		case "01/07": $ret="七草粥ですね！"; break;
		case "01/14": $ret="成人の日です！"; break;
		case "02/03": $ret="節分です！"; break;
		case "02/11": $ret="建国記念の日です！"; break;
		case "02/14": $ret="バレンタインデー・・・ですね///"; break;
		default: break;
	}
	return $ret;
}

// 嫁発言
function yome($text){
	if(strpos($text, "は俺の嫁") !== false){
	} else if(strpos($text, "は私の嫁") !== false){
	} else if(strpos($text, "はわたしの嫁") !== false){
	} else if(strpos($text, "はオイラの嫁") !== false){
	} else if(strpos($text, "ｍｋｙｍ") !== false){
	} else if(strpos($text, "みくよめ") !== false){
	} else if(preg_match("/[#＃].*嫁(もらい|貰い|にし)放題/u", $text) === 1){
	} else {
		return false;
	}
	return true;
}

// リプライあり・なし共通
function keyword($json)
{
	$message = false;
	$text = $json->text;
	
	if(preg_match("/(([1１一]番|いちばん)[い良]い.*?)を(頼|たの)む/u",
				$text, $match) === 1){
		$message = ".∵･(ﾟдﾟ(([".$match[1]."]＝＝＝＝＝ヾ(･∀･ヽ)どうぞっ";
	} else if((preg_match("/(なら|は)(任|まか)せろー/u", $text) === 1)
				&&(strpos($text,"やめて") === false)){
		$message = "＼やめて！／";
	} else if(preg_match("/[ｼシ][ｭュ][ウゥーｳｩｰ]{2,}[ｯッ]*[!！]/u", $text) === 1){
		$message = "＼超エキサイティンッ！／";
	} else if((preg_match("/じゃあ?、?いつ.*?[かの][\?？]$/", $text) === 1)
				&&(strpos($text, "でしょ") === false)){
		$message = "＼今でしょ！／";
	} else if(preg_match("/^(.+?)(は|を|って)(.*?)(で|だと|)(いくつ|いくら|計算)(だっけ|なの|ですか|して|すると)?？$/u"
						, str_replace("@Mint_bot ", "", $text), $match) === 1){
		$reply = strpos($text, "@Mint_bot");
		$message = google_calc($match[1], $match[3], $reply);
	} else if(preg_match("/(こんにゃく|蒟蒻|コンニャク)/u", $text) === 1){
		$message = konjac();
	} else if(preg_match("/(ﾋﾞｸbbﾆｸﾝ！！|びくん|ビクン|ﾋﾞｸﾝ|感じちゃう)/u",
							$text) === 1){
		$message = "＼ﾋﾞｸbbﾆｸﾝ！！／";
	} else if((strpos($text, "たんだよ！") !== false)
				&&(strpos($text, "？") === false)){
		$message = "(； ･`д･´) ﾅ､ﾅﾝﾀﾞｯﾃｰ !! (`･д´･ (`･д´･ ;)";
	} else if(strpos($text, "わんわんお") !== false){
		$message = "（Ｕ＾ω＾） わんわんお！";
	} else if(strpos($text, "にゃんにゃんお") !== false){
		$message = "(≡＾ω＾≡)にゃんにゃんお！";
	} else if(strpos($text, "ｶﾎﾞﾀｰﾝ") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ｶﾎﾞﾀｰﾝ!! ";
	} else if(strpos($text, "かぼたん") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ｶﾎﾞﾀｰﾝ!! ";
	} else if(strpos($text, "ﾆｹﾆｬｰﾝ") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ﾆｹﾆｬｰﾝ!!";
	} else if(strpos($text, "にけにゃん") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ﾆｹﾆｬｰﾝ!! ";
	} else if(strpos($text, "ﾀﾞｲﾁｬｰﾝ") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ﾀﾞｲﾁｬｰﾝ!!";
	} else if(strpos($text, "大ちゃん") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ﾀﾞｲﾁｬｰﾝ!!";
	} else if(strpos($text, "ｱｸﾆｬｰﾝ") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ｱｸﾆｬｰﾝ!!";
	} else if(strpos($text, "あくにゃん") !== false){
		$message = "Σｄ（ﾟ∀ﾟｄ）ｱｸﾆｬｰﾝ!!";
	} else if(strpos($text, "おっぱい") !== false){
		switch(rand(0,8)){
			case 0:
				$message = "残念でした。今回はおっぱいはありません・・・";
				break;
			case 1: case 2: case 3:
				$message = "ちょ、ちょっと何言ってるんですかっ///";
				break;
			case 4:
				$message = "おっぱい・・・触ってみたいですか？";
				$message.= "・・・ふふっ、冗談ですｗ";
				break;
			case 5: case 6:
				$message = "ふよん(((((･ω･)))))ふよん";
				break;
			default:
				$message = "(　ﾟ∀ﾟ)o彡゜おっぱい！おっぱい！";
				break;
		}
	} else if(strpos($text, "ミント") !== false){
		if((strpos($text, "ミントン") === false)
			&& (strpos($text, "ミントテ") === false)) {
			switch(rand(0,2)){
				case 0:
					$message = "ミントﾃﾞｰｽ！>v(*'ω'*v三v*'ω'*)v<ミントﾃﾞｰｽ！";
					break;
				case 1:
					$message = "右肘、左肘、交互に＞L('ω')┘三└('ω')」＜ﾐﾝﾄﾃﾞｰｽ！ﾐﾝﾄﾃﾞｰｽ！";
					break;
				default:
					$message = "[`⊙^⊙´]三[`⊙^⊙´]<ﾐﾝﾄだーよ！ﾐﾝﾄだーよ！";
					break;
			}
		}
	}
	
	return $message;
}

// こんにゃく
function konjac() {
	switch(rand(0,5)){
		case 0:	$message = "(」・ω・)」＝！(／・∀・)／＝＝＝＝＝["; break;
		default:$message = "(ﾉ・∀・)ﾉ　＝＝＝＝＝["; break;
	}
/*
	switch(rand(0,9)){
		case 0: $message .= "蒟蒻"; break;
		case 1: $message .= "蒟蒻ゼリー"; break;
		case 2: $message .= "熱々蒟蒻"; break;
		case 3: $message .= "蒟蒻芋"; break;
*/
/*
		case 4: $message .= "冷水"; break;
		case 5: $message .= "タオル"; break;
		case 6: $message .= "帽子"; break;
		case 7: $message .= "制汗スプレー"; break;
		case 8: $message .= "塩飴"; break;
		default:$message .= "合羽"; break;
*/
/*
		case 4: $message .= "おでん蒟蒻"; break;
		case 5: $message .= "あったかいお茶"; break;
		case 6: $message .= "マフラー"; break;
		case 7: $message .= "カイロ"; break;
		case 8: $message .= "合羽"; break;
//		case 8: $message .= "ウカール(センター仕様)"; break;
//		case 9: $message .= "キット勝ット(センター仕様)"; break;
		default:$message .= "手袋"; break;

	}
*/
	$message .= "蒟蒻";
	$message .= "]))ﾟДﾟ)･∵．";

	return $message;
}

// Google電卓
function google_calc($from, $to, $reply){
	if(strpos($from,"はっか") !== false){
		return "・・・ひみつっ！";
	}

	if($to !== ""){
		$exp = trim($from."を".$to."で");
	} else {
		$exp = trim($from);
	}
	print("Google Calc:".$exp.PHP_EOL);
	
	$ret = file_get_contents("http://www.google.co.jp/ig/calculator?oe=utf-8&q="
								.urlencode($exp));
	$ret = preg_replace("/([a-zA-Z0-9_]+?):/", "\"$1\":", $ret);
	$dec = json_decode($ret);
	if($dec->error !== ""){
		if($reply !== false){
			return "Google先生曰くエラー".$dec->error."だって(´･ω･`)";
		} else {
			return false;
		}
	}
	return "Google先生曰く【 ".$dec->lhs." = ".$dec->rhs." 】だって！"
			."https://www.google.co.jp/search?q=".urlencode($exp);
}

?>
