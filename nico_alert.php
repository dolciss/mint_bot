<?php
// nico_alert.php
// ニコニコアラート

require_once("HTTP/Request2.php");
require_once("Twitter.php");
require_once("Nicovideo.php");

// defined NICONICO_MAIL, NICONICO_PASS
require_once("Secret.php");

define("USER_AGENT", "NicoAlert2Twitter/1.1 (by @L_tan)");
$mail = NICONICO_MAIL;
$pass = NICONICO_PASS;

print("Niconico Login->");
if(($cookie = getCookie($mail, $pass)) === false){
	print("NG...".PHP_EOL);
	exit();
}
print("OK.".$cookie["name"].":".$cookie["value"].PHP_EOL);

print("GetTicket->");
if(($ticket = getTicket($mail, $pass)) === false){
	print("NG...".PHP_EOL);
	exit();
}
print("OK.".$ticket.PHP_EOL);

print("GetAlertStatus->");
if(($status = getAlertStatus($ticket)) === false){
	print("NG...".PHP_EOL);
	exit();
}
print("OK.".PHP_EOL);

print("Connect to ".$status["addr"].":".$status["port"]."->");
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(!is_resource($socket)){
	$error = socket_strerror(socket_last_error());
	print("socket_create():".$error."->NG...".PHP_EOL);
	exit();
}
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO
					, array("sec"=>300, "usec"=>0));
$ip = gethostbyname($status["addr"]);
$po = intval($status["port"]);
if(($ret = socket_connect($socket, $ip, $po)) === false){
	$error = socket_strerror(socket_last_error($socket));
	print("socket_connect():".$error."->NG...".PHP_EOL);
	socket_close($socket);
	exit();
}
print("OK.".PHP_EOL);

foreach($status["services"] as $id => $thread){
	print("SendThread ".$id.":".$thread."->");
	$data = "<thread thread=\"".$thread."\" version=\"20061206\""
			." res_from=\"-1\" scores=\"1\"/>\0";
	$ret = socket_write($socket, $data);
	if(($ret === false)||($ret != strlen($data))){
		$error = socket_strerror(socket_last_error($socket));
		print("socket_write():".$error."->NG...".PHP_EOL);
		socket_close($socket);
		exit();
	}
	print("OK.".PHP_EOL);
}

print("ReadLoopStart...".PHP_EOL);
while(1){
	if(($read = socket_read($socket, 1024)) === false){
		break;
	}
	$read = strtr($read, "\0", "\n");
	if(!(preg_match_all("#(.*?)(<[^>]+?\/>|<[^>]+?>.*?</[^>]+?>)(.*?)#u",
		$read, $matches, PREG_SET_ORDER) > 0)){
		print("++++++TagNotFound++++++".PHP_EOL);
		print($read.PHP_EOL);
		print("+++++++++++++++++++++++".PHP_EOL);
		continue;
	}
	foreach($matches as $part){
		if($part[1] !== ""){
			print("++++++Tag Before++++++".PHP_EOL);
			print($part[1].PHP_EOL);
			print("++++++++++++++++++++++".PHP_EOL);
		}
		$xml = simplexml_load_string($part[2]);
		switch($xml->getName()){
			case "thread":
				print("Thread:".$xml["thread"]."(");
				print(array_search((string)$xml["thread"], $status["services"]));
				print(")->".$xml["resultcode"].PHP_EOL);
				break;
			case "chat":
				/*
				print("Chat:".$xml["thread"]."(");
				print(array_search((string)$xml["thread"], $status["services"]));
				print(")->".$part[2].PHP_EOL);
				*/
				$data = explode(",", $xml);
				if(((int)$xml["date"] - (int)$data[0]) < 2){
					print("++++++Fixed Time (xml:");
					print($xml["date"]." data:".$data[0]."->");
					print(date("Y/m/d H:i:s", $data[0]));
					print(")++++++".PHP_EOL);
					break; // switch($xml->getName())
				}
				switch(array_search((string)$xml["thread"], $status["services"])){
					case "live":
						switch(count($data)){
							case 1:
								live_start($data[0], "coxxxxxx", "xxxxxxxx");
								break;
							case 2:
								live_start($data[0], $data[1], "xxxxxxxx");
								break;
							default:
								live_start($data[0], $data[1], $data[2]);
								break;
						}
						break;
					case "video":
						if(count($data) < 2){
							print("++++++ID Not Enough++++++".PHP_EOL);
							print($part[2].PHP_EOL);
							print("+++++++++++++++++++++++++".PHP_EOL);
						} else {
							video_post($data[0], $data[1]);
						}
						break;
					case "seiga":
						if(count($data) < 2){
							print("++++++ID Not Enough++++++".PHP_EOL);
							print($part[2].PHP_EOL);
							print("+++++++++++++++++++++++++".PHP_EOL);
						} else {
							seiga_post($data[0], $data[1]);
						}
						break;
					default:
						print("++++++Unknown Thread++++++".PHP_EOL);
						print($part[2].PHP_EOL);
						print("++++++++++++++++++++++++++".PHP_EOL);
						break;
				}
				break;
			default:
				print("++++++Unknown Tag++++++".PHP_EOL);
				print($part[2].PHP_EOL);
				print("+++++++++++++++++++++++".PHP_EOL);
				break;
		} // switch
		if($part[3] !== ""){
			print("++++++Tag After++++++".PHP_EOL);
			print($part[1].PHP_EOL);
			print("+++++++++++++++++++++".PHP_EOL);
		}
	} // foreach
	if(($pid = pcntl_wait($stat, WNOHANG)) > 0){
		print("Child Process (".$pid.") Dead.".PHP_EOL);
	}
}
print("ReadLoopEnd.".PHP_EOL);
socket_close($socket);
statuses_update($mint_bot_m, "@L_tan 接続がタイムアウトしたのでアラート機能停止します～");
print("Child Process Wait...".PHP_EOL);
$pid = pcntl_wait($stat);
print("Child Process (".$pid.") Dead.".PHP_EOL);
exit();

// functions
function getCookie($mail, $pass)
{
	$request_url = "https://secure.nicovideo.jp/secure/login?site=niconico";
	
	try{
		$request = new HTTP_Request2($request_url);
		$request->setConfig("ssl_verify_peer", false);
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->setHeader(array("User-Agent" => USER_AGENT));
		$request->addPostParameter("mail", $mail);
		$request->addPostParameter("password", $pass);
		
		$result = $request->send();
		
		$ret = array();
		foreach($result->getCookies() as $cookie){
			if($cookie["value"] != "deleted"){
				$ret["name"] = $cookie["name"];
				$ret["value"] = $cookie["value"];
			}
		}
		if(count($ret) < 2){
			return false;
		}
		return $ret;
	} catch(HTTP_Request2_Exception $e){
		print("HTTP_Request2_Exception:".$e->getMessage().PHP_EOL);
		return false;
	} catch(Exception $e){
		print("Exception:".$e->getMessage().PHP_EOL);
		return false;
	}
}

function getTicket($mail, $pass)
{
	$request_url = "https://secure.nicovideo.jp/secure/login?site=nicoalert";
	
	try{
		$request = new HTTP_Request2($request_url);
		$request->setConfig("ssl_verify_peer", false);
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->setHeader(array("User-Agent" => USER_AGENT));
		$request->addPostParameter("mail", $mail);
		$request->addPostParameter("password", $pass);
		
		$result = $request->send();
		$xml = simplexml_load_string($result->getBody());
		if(!isset($xml["status"])
			||!isset($xml->ticket)
			||($xml["status"] == "fail")){
			return false;
		}
		return (string)$xml->ticket;
	} catch(HTTP_Request2_Exception $e){
		print("HTTP_Request2_Exception:".$e->getMessage().PHP_EOL);
		return false;
	} catch(Exception $e){
		print("Exception:".$e->getMessage().PHP_EOL);
		return false;
	}
}

function getAlertStatus($ticket)
{
//	$request_url = "http://alert.nicovideo.jp/front/getalertstatus";
	$request_url = "https://live.nicovideo.jp/api/getalertstatus";
	
	try{
		$request = new HTTP_Request2($request_url);
		$request->setConfig("ssl_verify_peer", false);
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->setHeader(array("User-Agent" => USER_AGENT));
		$request->addPostParameter("ticket", $ticket);
		
		$result = $request->send();
		$xml = simplexml_load_string($result->getBody());
		if(!isset($xml->attributes()->status)||($xml->attributes()->status == "fail")){
			return false;
		}
		if(!isset($xml->ms)){
			return false;
		}
		$ret = array();
		if(!isset($xml->ms->addr)||!isset($xml->ms->port)){
			return false;
		}
		$ret["addr"] = $xml->ms->addr;
		$ret["port"] = $xml->ms->port;
		$ret["services"]["live"] = $xml->ms->thread;
		$ret["services"]["video"] = 1000000002;
		$ret["services"]["seiga"] = 1000000003;
/*
		if(!isset($xml->services->service)){
			return false;
		}
		foreach($xml->services->service as $service){
			$ret["services"][(string)$service->id] = $service->thread;
		}
*/
		return $ret;
	} catch(HTTP_Request2_Exception $e){
		print("HTTP_Request2_Exception:".$e->getMessage().PHP_EOL);
		return false;
	} catch(Exception $e){
		print("Exception:".$e->getMessage().PHP_EOL);
		return false;
	}
}

function live_start($liveid, $commid, $userid)
{
	global $cookie;
	global $mint_bot_m;
	global $taizoo_bot;
	static $lastlive;
	
	print(date("Y/m/d H:i:s"));
	print(" lv:".$liveid.", commid:".$commid.", userid:".$userid."->");
	
	if($lastlive == $liveid){
		print("duplicate.".PHP_EOL);
		return;
	}
	$lastlive = $liveid;

	include("alert_live.php");
	if(in_array("lv".$liveid, $livelist)){
		$hit = "live";
	} else if (in_array($commid, $commlist)){
		$hit = "comm";
	} else if ($commid === "official"){
		$hit = "offi";
	} else if (in_array($userid, $userlist)){
		$hit = "user";
	} else if(preg_match("/^c[oh]\d+/u", $commid) === 0){
		// commidに放送タイトルが入ってる場合
		$hit = "offi";
	} else {
		print("nohit.".PHP_EOL);
		return;
	}
	print($hit."hit.".PHP_EOL);

	// multi-process
	$pid = pcntl_fork();
	if($pid == -1){
		print("Fork Failed...".PHP_EOL);
		return;
	} else if($pid){
		// parent process
		print("Parent Process Fork(".$pid.") Loop.".PHP_EOL);
		return;
	}
	// child process (return禁止!)
	print("Child Process (".getmypid().") Start.".PHP_EOL);
	
	$title = ""; $owner_name = ""; $hashtag = ""; $supple = "";
	$url = "http://watch.live.nicovideo.jp/api/getplayerstatus?v=lv".$liveid;
	try{
		$request = new HTTP_Request2($url);
		$request->setHeader(array("User-Agent" => USER_AGENT));
		$request->addCookie($cookie["name"], $cookie["value"]);
		
		$result = $request->send();
		
		$xml = simplexml_load_string($result->getBody());
		if(!isset($xml["status"])||($xml["status"] == "fail")){
			print("lv".$liveid." GetPlayerStatus Failed...");
			if(isset($xml->error->code)){
				print(" ErrorCode:".$xml->error->code);
			}
			print(PHP_EOL);
		} else {
			print("lv".$liveid." Title:GetPlayerStatus".PHP_EOL);
			$title = $xml->stream->title;
			$owner_name = $xml->stream->owner_name;
			if((string)$xml->stream->provider_type == "official"){
				$owner_name = "公式";
			}
			$hashtag = $xml->stream->twitter_tag;
			if(time()+60 < $xml->stream->start_time){
				$supple = date("(H:i開始)", intval($xml->stream->start_time));
			}
		}
	} catch(HTTP_Request2_Exception $e){
		print("HTTP_Request2_Exception:".$e->getMessage().PHP_EOL);
	} catch(Exception $e){
		print("Exception:".$e->getMessage().PHP_EOL);
	}
	
	$ret = file_get_contents("http://live.nicovideo.jp/gate/lv".$liveid);
	if(preg_match("/og:title\" content=\"(.*?)\"/u", $ret, $match) === 1){
		print("lv".$liveid." Title:GatePage".PHP_EOL);
		$title = htmlspecialchars_decode($match[1], ENT_QUOTES);
	}
	if($title == ""){
		$ret = file_get_contents("http://live.nicovideo.jp/watch/lv".$liveid);
		if(preg_match("/og:title\" content=\"(.*?)\"/u", $ret, $match) === 1){
			print("lv".$liveid." Title:WatchPage".PHP_EOL);
			$title = htmlspecialchars_decode($match[1], ENT_QUOTES);
		}
	}
	if(preg_match("/provider_type: \"official\"/", $ret) === 1){
		$hit = "offi"; $owner_name = "公式";
	}
	if($title == ""){
		print("lv".$liveid." Gate/WatchPage Failed...".PHP_EOL);
		file_put_contents("log/lv".$liveid.".log", $ret);
		if(($hit == "offi")&&($commid !== "official")){
			$title = $commid;
		} else {
			$title = "？？？";
		}
	}
	if($owner_name == ""){
		/*
		// ログイン時しか使えなさそう(´･ω･`)
		if(preg_match("/class=\"company\"\s*title=\"(.*?)\"/u",
			$ret, $match) === 1){
			$owner_name = htmlspecialchars_decode($match[1]);
		} else
		*/
		if(preg_match("/(放送者|提供)[:：](\s|<.*?>)*(.*?)(<.*?>)+/u",
			$ret, $match) === 1){
			$owner_name = htmlspecialchars_decode($match[3]);
		} else if($hit === "offi"){
			$owner_name = "公式";
		} else {
			$owner_name = "？？？";
		}
	}
	if($hashtag == ""){
		if(preg_match("/hashTags&quot;:\[(.*?)\]/u", $ret, $match) === 1){
			$hashtag = " #".implode(" #", preg_split("/,/", str_replace("&quot;","",$match[1])));
		} else {
			$hashtag = " #".$commid;
		}
	}
	if(($hit == "offi")
		&&(strpos($ret, "電波諜報局") === FALSE)
		&&(strpos($ret, "「アイドルマスターシンデレラガールズ」") === FALSE)
		&&(strpos($ret, "「アイマス」尽くしの") === FALSE)
		&&(strpos($ret, "ニコニコアニメチャンネルでは") === FALSE)){
		print("lv".$liveid." Not Anime Official...".PHP_EOL);
	} else {
		if($hit == "offi"){
			if(preg_match("/(第[^<]*?[^話])(<\/strong>|<\/b><BR>)/ui", $ret, $match) === 1){
				print("lv".$liveid." Anime Official Get SubTitle".PHP_EOL);
				$title .= " ".htmlspecialchars_decode($match[1]);
			}
		}
		if($commid == "co1031128"){
			$message = make_message("おや、どうやら".$owner_name."さんが生放送",
									$title, $supple, "を始めたようだな・・・",
									"http://nico.ms/lv".$liveid, "");
			tweet_message($taizoo_bot, $message, null, 0);
		} else {
			$message = make_message("あら、".$owner_name."さんの生放送",
									$title, $supple, "が始まったようね。",
									"http://nico.ms/lv".$liveid, $hashtag);
			tweet_message($mint_bot_m, $message, null, 0);
		}
	}
	
	print("Child Process (".getmypid().") End.".PHP_EOL);
	exit();
}

function video_post($mediid, $userid)
{
	global $mint_bot_m;
	static $lastvideo;
	
	print(date("Y/m/d H:i:s"));
	print(" id:".$mediid.", userid:".$userid."->");
	
	if($lastvideo == $mediid){
		print("duplicate.".PHP_EOL);
		return;
	}
	$lastvideo = $mediid;

	include("alert_media.php");
		if(in_array(ltrim($mediid,"a..z"), $medilist)){
		$hit = "medi";
	} else if (array_key_exists($userid, $userlist)){
		if(substr($userid, 0, 2) == "ch"){
			$hit = "chan";
		} else {	
			$hit = "user";
		}
	} else {
		print("nohit.".PHP_EOL);
		return;
	}
	print($hit."hit.".PHP_EOL);

	// multi-process
	$pid = pcntl_fork();
	if($pid == -1){
		print("Fork Failed...".PHP_EOL);
		return;
	} else if($pid){
		// parent process
		print("Parent Process Fork(".$pid.") Loop.".PHP_EOL);
		return;
	}
	// child process (return禁止!)
	print("Child Process (".getmypid().") Start.".PHP_EOL);
	
	if(isset($userlist[$userid])){
		$name = $userlist[$userid];
	} else {
		$name = null;
	}
	$title = "";
	$smid = "";
	$time = "";
	$ret = file_get_contents("http://ext.nicovideo.jp/api/getthumbinfo/".$mediid);
	$thumbinfo = simplexml_load_string($ret);
	if($thumbinfo["status"] != "ok"){
		print($mediid." GetThumbInfo Failed...".PHP_EOL);
	} else if($thumbinfo->thumb->first_retrieve == 0){
		print($mediid." FirstRetrieve Zero...".PHP_EOL);
	} else {
		print($mediid." Title:GetThumbInfo".PHP_EOL);
		$title = htmlspecialchars_decode($thumbinfo->thumb->title);
		$smid = $thumbinfo->thumb->video_id;
		if(isset($thumbinfo->thumb->length)){
			$time = "(".$thumbinfo->thumb->length.")";
		}
	}
	if($title == ""){
		$ret = file_get_contents("http://www.nicovideo.jp/watch/".$mediid);
		if(preg_match("/og:title\" content=\"(.*?)\"/u", $ret, $match) === 1){
			print($mediid." Title:WatchPage".PHP_EOL);
			$title = htmlspecialchars_decode($match[1]);
		} else {
			print($mediid." WatchPage Failed...".PHP_EOL);
		}
		if(preg_match("/vinfo_length\"><span>(.*?)</", $ret, $match) === 1){
			$time = "(".$match[1].")";
		}
	}
	if($title == ""){
		$ret = file_get_contents("http://i.nicovideo.jp/v3/video.array?v=".$mediid);
		$xml = simplexml_load_string($ret);
		if($xml["status"] == "ok"){
			print($mediid." Title:VideoArray".PHP_EOL);
			$title = htmlspecialchars_decode($xml->video_info->video->title);
			$smid = $xml->video_info->video->id;
			$time_sec = $xml->video_info->video->length_in_seconds;
			$time = sprintf("(%d:%02d)", floor($time_sec/60), $time_sec%60);
		} else {
			print($mediid." VideoArray Failed...".PHP_EOL);
		}
	}
	$ret = file_get_contents("http://api.ce.nicovideo.jp/nicoapi/v1/video.info?__format=json&v=".$mediid);
	$json = json_decode($ret);
	if($json->nicovideo_video_response->{'@status'} == "ok"){
		$json = $json->nicovideo_video_response->video;
		if($title == "") {
			$title = htmlspecialchars_decode($json->title);
			$smid = $json->id;
			$time_sec = $json->length_in_seconds;
			$time = sprintf("(%d:%02d)", floor($time_sec/60), $time_sec%60);
		}
		if($json->ppv_video == 1) {
			$ppv = "[有料]";
		} else {
			$ppv = "";
		}
	} else {
		print($mediid." Vita API Failed...".PHP_EOL);
		if($title == "") {
			$title = "？？？";
		}
	}
	
	if($smid == ""){
		$smid = $mediid;
	}
	$header = get_headers("http://www.nicovideo.jp/watch/".$smid, 1);
	if(isset($header["Location"])){
		if(is_array($header["Location"])){
			$videoid = basename(array_pop($header["Location"]));
			if(strpos($videoid, "login_form") !== false) {
				$videoid = basename(array_pop($header["Location"]));
			}
		} else {
			$videoid = basename($header["Location"]);
		}
	} else {
		$videoid = $smid;
	}
	
	switch($hit){
		case "chan":
			if($title != "？？？"){
				$before = "まぁ、チャンネル動画";
			} else {
				if($name !== null){
					$before = "まぁ、".$name."チャンネルに動画";
				} else {
					$before = "まぁ、？？？チャンネルに動画";
				}
			}
			$after = $ppv."が投稿されたようですよ。";
			$url = "http://nico.ms/".$videoid;
			$hashtag = "#nicoch #".$smid;
			break;
		default:
			if($name !== null){
				$before = "まぁ、".$name."が動画";
			} else {
				$before = "まぁ、？？？さんが動画";
			}
			$after = "を投稿されたようですよ。";
			$url = "http://nico.ms/".$smid;
			$hashtag = "#".$smid;
			break;
	}
	$message = make_message($before, $title, $time, $after, $url, $hashtag);
	tweet_message($mint_bot_m, $message, null, 0);
	
	if($hit == "chan"){
		print($videoid." FetchWait...".PHP_EOL);
		sleep(10);
		nicocache_fetch($videoid);
	}
	
	print("Child Process (".getmypid().") End.".PHP_EOL);
	exit();
}

function seiga_post($mediid, $userid)
{
	global $mint_bot_m;
	static $lastseiga;
	
	print(date("Y/m/d H:i:s"));
	print(" id:".$mediid.", userid:".$userid."->");
	
	if($lastseiga == $mediid){
		print("duplicate.".PHP_EOL);
		return;
	}
	$lastseiga = $mediid;

	include("alert_media.php");
	if(in_array(ltrim($mediid,"a..z"), $medilist)){
		$hit = "medi";
	} else if (array_key_exists($userid, $userlist)){
		if(substr($userid, 0, 2) == "ch"){
			$hit = "chan";
		} else {	
			$hit = "user";
		}
	} else {
		print("nohit.".PHP_EOL);
		return;
	}
	print($hit."hit.".PHP_EOL);

	// multi-process
	$pid = pcntl_fork();
	if($pid == -1){
		print("Fork Failed...".PHP_EOL);
		return;
	} else if($pid){
		// parent process
		print("Parent Process Fork(".$pid.") Loop.".PHP_EOL);
		return;
	}
	// child process (return禁止!)
	print("Child Process (".getmypid().") Start.".PHP_EOL);

	$ret = file_get_contents("http://nico.ms/".$mediid);
	if(preg_match("/<title>(.*?) \/ .*? さんのイラスト/u",
		$ret, $match) === 1){
		$title = htmlspecialchars_decode($match[1]);
	} else if(preg_match("/og:title\" content=\"(.*?)\"/u",
		$ret, $match) === 1){
		$title = htmlspecialchars_decode($match[1]);
	} else {
		print($mediid." Title Not Found...".PHP_EOL);
		$title = "？？？";
	}
	if(isset($userlist[$userid])){
		$name = $userlist[$userid];
	} else {
		$name = "？？？さん";
	}
	$message = make_message("まぁ、".$name."が静画",
							$title,
							"を投稿されたようですよ。",
							"",
							"http://nico.ms/".$mediid,
							"#".$mediid." #nicoseiga");
	tweet_message($mint_bot_m, $message, null, 0);

	print("Child Process (".getmypid().") End.".PHP_EOL);
	exit();
}

?>
