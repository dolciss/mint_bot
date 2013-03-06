<?php
// apricat_bot.php
// あんずにゃん

require_once("Twitter.php");

user_stream($apricat_bot, array(), "apricat_stream");
status_update($apricat_bot, "@L_tan UserStream切れたみゃ～(=^･ω･^=)");
exit();

function apricat_stream($data)
{
	global $apricat_bot;
	static $retweet = array();
	$json = json_decode(strstr($data, "{"));
	if(isset($json->text)){
		print(date("Y/m/d H:i:s", strtotime($json->created_at)).":");
		print($json->user->name."(@".$json->user->screen_name.")");
		print(" via ".strip_tags($json->source).PHP_EOL);
		print($json->text.PHP_EOL."====> ");
		if(($ret = postcheck($json)) !== false){
			print("PostTweetRT(id:".$ret[1].")".PHP_EOL);
			$retweet[$ret[0]] = $ret[1];
		} else {
			print(reply($json).PHP_EOL);
		}
	} else if(isset($json->delete)){
		print("Delete:".$json->delete->status->id." => ");
		if(isset($retweet[$json->delete->status->id])){
			$destroy = $retweet[$json->delete->status->id];
			print("RT:".$destroy.PHP_EOL);
			statuses_destroy($apricat_bot, $destroy);
		} else {
			print("Not RT.".PHP_EOL);
		}
	} else if(isset($json->disconnect)){
		print("Disconnect Stream:".$json->disconnect->code);
		print("(".$json->disconnect->stream_name.")");
		print(" => ".$json->disconnect->reason.PHP_EOL);
	} else {
		print("Unprocess Json.".PHP_EOL);
		print_r($json);
	}
}

// 投稿をRT
function postcheck($json)
{
	global $apricat_bot;
	$screen_name = $json->user->screen_name;
	$name = trim_name($screen_name, $json->user->name);
	
	if((strpos($json->source, "ニコニコ動画<") !== false)
		&&(strpos($json->text, "投稿しました。") !== false)
		&&($json->user->protected != true)){
			$message = $name."！ RT @".$screen_name.": ".$json->text;
			$ret = tweet_message($apricat_bot, $message, null, 0);
			if(isset($ret->id)){
				return array($json->id, $ret->id);
			}
	}
	return false;
}

// 返事
function reply($json)
{
	global $apricat_bot;
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
	if(strpos($screen_name, "Apricat_bot") !== false){
		// 自分の発言には反応しない
		return "SelfTweet.";
	}

	// とりあえず自分宛を省いて、他にも@があったら反応しない
	$text = str_replace("@Apricat_bot", "", $text, $count);
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

	tweet_message($apricat_bot, $message, $reply_id, $wait);
	return "";
}

function mention($json)
{
	$message = "@".$json->user->screen_name." ";
	$name = trim_name($json->user->screen_name, $json->user->name);
	$text = $json->text;
	$wait = null;
	
	if(preg_match("/(ご苦労)|(お(疲|つか)れ)|(げんき|元気|[い生]きてる)(ですか|ー?？)/u",
					$text) === 1){
		switch(rand(0,2)){
			case 0:  $message .= "元気にがんばってるみゃよ～"; break;
			case 1:
				$message .= "みゃぅ…はっ、ねてない、寝てないよみょ"; break;
			default:
				$message .= "今日もおしごとみゃー♪"; break;
		}
	} else if(preg_match("/((あい|まい|愛)して[るう])|(([す好]き|らぶ)[やでっよだー！])/u", $text) === 1) {
		switch(rand(0,2)){
			case 0:  $message .= "あ、ありがとうございみゃっ♪"; break;
			case 1:  $message .= "わ、わたしも".$name."さんのこと…";
					 $message .= "　ってなにいってんのかみゃっ///"; break;
			default: $message .= "みゃーそういうのだめっ///"; break;
		}
	} else if(preg_match("/(かわ|可愛)[ゆい][いおよっね！…]/u", $text) === 1){
		switch(rand(0,2)){
			case 0:  $message .= "あ、ありがとう…///"; break;
			case 1:  $message .= "や、やめるみゃーｗ"; break;
			default: $message .= "そ、そんなこと・・・ない・・・///"; break;
		}
	} else if(preg_match("/(ちゅっ|(はぁ|ハァ|ﾊｧ|はす|ハス|ﾊｽ|ぺろ|ペロ|ﾍﾟﾛ)+)/u", $text) === 1){
		$message .= "みゃふふ///";
	} else if(preg_match("/(もふ|モフ|ﾓﾌ|つん|ふも|ぎゅっ|なで)+/u",
				$text, $match) === 1){
		switch(rand(0,2)){
			case 0: $message .= "みぅ・・・///"; break;
			case 1: $message .= "ちょ、ちょっとっ・・・みゃふーｗ"; break;
			default:$message .= $match[1].$match[1]."ー///"; break;
		}
	} else if(preg_match("/((もみ|もみっ|ぱふ|さわ|モミ|ﾓﾐ)+|ぎゅ)/u", $text) === 1){
		switch(rand(0,2)){
			case 0: $message .= "みゃっ！・・・みぅ///"; break;
			case 1: $message .= "・・・みゃーっｗ"; break;
			default:$message .= "え、えっちなのは・・・ちょっと///";
					break;
		}
	} else if(preg_match("/[も揉][みむん]/u", $text) === 1){
		$message .= "みゃ、みゃうーっ！///";
	} else if(preg_match("/(策士|優秀|えらい|すごい)/u", $text) === 1){
		$message .= "えへへ、ありがとうみゃっ";
	} else if(preg_match("/も(よろ|宜)しく/u", $text) === 1){
		$message .= "こちらこそみゃー";
	} else if(strpos($text, "ありがと") !== false){
		$message .= "いえいえ～";
	} else if(strpos($text, "呼んだ") !== false){
		$message .= "みゃうっw";
	} else if(strpos($text, "///") !== false){
		$message .= "んみゃ・・・///";
	} else if(preg_match("/[ｗw]{3,}/u", $text) === 1){
		$message .= "わ、わらいすぎみゃ…ｗ";
	} else {
		switch(rand(0,3)){
			case 0:  $message .= "うみゃ？ｗ"; break;
			case 1:  $message .= "うみゅｗ"; break;
			case 2:  $message .= "そうみゃ・・・"; break;
			default: $message .= "( ･∀･)つ〃∩ ﾍｪｰ×".rand(0,99); break;
		}
	}
	
	return array($message, $wait);
}

function timeline($json)
{
	if(preg_match("/あんずにゃー?ん/u", $json->text) === 1){
		$message = "@".$json->user->screen_name." ";
		switch(rand(0,49)){
			case 0:  $message .= "呼んだかみゃ～？"; break;
			default: $message .= "呼ばれた気がするみゃっ！"; break;
		}
		return array($message, null);
	}
	return array(false, null);
}

?>