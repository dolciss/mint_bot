<?php
// Nicovideo.php
// ニコニコ動画関連

require_once("Twitter.php");

function nicocache_fetch($videoid)
{
	//Nicocache fetch
	$proxy = array(
				"http" => array(
				"proxy" => "tcp://127.0.0.1:2525",
				"request_fulluri" => true
				)
			);
	$sc = stream_context_create($proxy);
	$ret = file_get_contents(
			"http://www.nicovideo.jp/cache/fetch?".$videoid,
			false, $sc);
	print($videoid."->".$ret.PHP_EOL);
}

function make_message($before, $title, $supple, $after, $url, $hashtag)
{
	$length = 0;
	
	list($short_url_length, $short_url_length_https)
		= get_short_url_length();
	
	// 必須項目
	$length += mb_strlen($before);
	$length++; // "「"の分
	$length += mb_strlen($title);
	$length++; // "」"の分
	$length += mb_strlen($after);
	$length++; // " "の分
	if(stripos($url, "https") === 0){
		$length += $short_url_length_https;
	} else {
		$length += $short_url_length;
	}
	
	// タイトルは短縮可
	if($length > 140){
		$over = $length - 140;
		$over++; // "…"の分
		$title = mb_substr($title, 0, -$over)."…";
	}
	
	// 付加情報は超えるなら入れない
	$length += mb_strlen($supple);
	if($length > 140){
		$supple = "";
	}
	
	// ハッシュタグも超える分だけ入れない
	$length ++; // " "の分
	$length += mb_strlen($hashtag);
	if($length > 140){
		$hashtag = "";
		$hashtags = explode(" ", $hashtag);
		$over = $length - 140;
		while(count($hashtags) !== 0){
			$h = array_pop($hashtags);
			if($over <= 0){
				$hashtag .= $h." ".$hashtag;
			} else {
				$over -= mb_strlen($h);
				$over--; // " "の分
			}
		}
	}
	if($hashtag !== ""){
		$hashtag = " ".$hashtag;
	}
	
	return $before."「".$title."」".$supple.$after." ".$url.$hashtag;
}

?>