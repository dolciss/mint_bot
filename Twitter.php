<?php
// Twitter.php
// REST APIまとめ

require_once("HTTP/OAuth/Consumer.php");
require_once("HTTP/Request2/Adapter/Curl.php");
require_once("Secret.php");
require_once("Regex.php");
require_once("EConfig.php");

mb_internal_encoding("UTF-8");

$http_request = new HTTP_Request2();
$http_request->setConfig("ssl_verify_peer", false);
$http_request->setHeader(array('User-Agent'=>'Mint_bot/1.1 (by @L_tan)'));
$consumer_request = new HTTP_OAuth_Consumer_Request();
$consumer_request->accept($http_request);

// はっかうさぎ@Mint_bot
$mint_bot_m = new HTTP_OAuth_Consumer(
					CONSUMER_KEY_MINT_BOT,
					CONSUMER_SECRET_MINT_BOT,
					ACCESS_TOKEN_MINT_BOT,
					ACCESS_SECRET_MINT_BOT
					);
$cr_mbm = clone $consumer_request;
$mint_bot_m->accept($cr_mbm);

// はっかうさぎ@L_tan
$mint_bot_l = new HTTP_OAuth_Consumer(
					CONSUMER_KEY_MINT_BOT,
					CONSUMER_SECRET_MINT_BOT,
					ACCESS_TOKEN_L_TAN,
					ACCESS_SECRET_L_TAN
					);
$cr_mbl = clone $consumer_request;
$mint_bot_l->accept($cr_mbl);

// あんずにゃん@Apricat_bot
$apricat_bot= new HTTP_OAuth_Consumer(
					CONSUMER_KEY_APRICAT_BOT,
					CONSUMER_SECRET_APRICAT_BOT,
					ACCESS_TOKEN_APRICAT_BOT,
					ACCESS_SECRET_APRICAT_BOT
					);
$cr_ab = clone $consumer_request;
$apricat_bot->accept($cr_ab);

// さいたまのパチ屋@TAIZOO_bot
$taizoo_bot = new HTTP_OAuth_Consumer(
					CONSUMER_KEY_TAIZOO_BOT,
					CONSUMER_SECRET_TAIZOO_BOT,
					ACCESS_TOKEN_TAIZOO_BOT,
					ACCESS_SECRET_TAIZOO_BOT
					);
$cr_tb = clone $consumer_request;
$taizoo_bot->accept($cr_tb);

// Extract Class
/* Example:
	$ext = Tweet_Extract::create("tweet text");
	$ext->extractTweet();
 */
class Tweet_Extract extends Twitter_Regex
{
	public static function create($tweet)
	{
		return new self($tweet);
	}
	public function __construct($tweet)
	{
		parent::__construct($tweet);
	}
	public function extractTweet()
	{
		if(preg_match_all(self::$REGEX_VALID_URL, $this->tweet,
								$matches, PREG_OFFSET_CAPTURE) > 0){
			$ret = array();
			$pos = 0;
			foreach($matches[2] as $url){
				$ret[] = array(mb_strcut($this->tweet, $pos, $url[1] - $pos), false);
				$ret[] = array($url[0], true);
				$pos = $url[1] + strlen($url[0]);
			}
			$ret[] = array(mb_strcut($this->tweet, $pos), false);
			return $ret;
		}
		return array(array($this->tweet, false));
	}
}

// REST API
function twitter_access(&$consumer, $resource_url, $arg = array(), $method = "POST")
{
	try {
		// Post Data Clear
		$consumer->getOAuthConsumerRequest()->setBody("");
		$ret = $consumer->sendRequest($resource_url, $arg, $method);
		if($ret->getHeader("x-rate-limit-limit") !== null){
			print(basename($resource_url).":");
			print($ret->getHeader("x-rate-limit-remaining")."/");
			print($ret->getHeader("x-rate-limit-limit").":Reset(");
			print(date("Y/m/d H:i:s", $ret->getHeader("x-rate-limit-reset")).")".PHP_EOL);
		}
		$result = json_decode($ret->getBody());
		if($result === NULL){
			print("Twitter API Not Result Error. ".$result->error.PHP_EOL);
			return false;
		} else if(isset($result->errors)){
			foreach($result->errors as $error){
				print("Twitter API Result Error. ");
				if(isset($error->message)){
					print("Message:".$error->message." ");
				}
				if(isset($error->code)){
					print("Code:".$error->code);
				}
				print(PHP_EOL);
			}
			return false;
		}
	} catch (Exception $e) {
		print("Exception. ".$e->getMessage().PHP_EOL);
		return false;
	}
	return $result;
}
// Streaming API
function twitter_stream(&$consumer, $resource_url, $arg = array(), $callback)
{
	$consumer_stream = create_consumer_stream($consumer, $callback);
	$errortype = "normal";
	$waitms = 250;
	while(1){
		try{
			$ret = $consumer_stream->sendRequest($resource_url, $arg, "GET");
			switch($ret->getStatus()){
				case 200: $errortype = "normal"; break;
				case 420: $errortype = "rate"; break;
				default: $errortype = "http"; break;
			}
		} catch(HTTP_Request2_Exception $e){
			$errortype = "network";
			print("HTTP Exception => ".$e->getMessage().PHP_EOL);
		} catch(Exception $e){
			print("Exception => ".$e->getMessage().PHP_EOL);
			if($e->getCode() == 30){
				// timeout
				$errortype = "normal";
			} else {
				$errortype = "network";
			}
		}
		print("Wait for ".$waitms."ms...".PHP_EOL);
		switch($errortype){
			case "normal":
				$waitms = 250;
				$nextwait = 250;
				break;
			case "network":
				if($waitms < 250){
					$waitms = 250;
				}
				$nextwait = $waitms + 250;
				if($nextwait > 16000){
					return;
				}
				break;
			case "http":
				if($waitms < 5000){
					$waitms = 5000;
				}
				$nextwait = $waitms * 2;
				if($nextwait > 320000){
					return;
				}
				break;
			case "rate":
				if($waitms < 60000){
					$waitms = 60000;
				}
				$nextwait = $waitms * 2;
				break;
			default:
				break;
		}
		usleep($waittime*1000);
		$waitms = $nextwait;
	}
}
class Observer_Custom implements SplObserver
{
	private $callback;
	
	public function __construct($callback_)
	{
		$this->callback = $callback_;
	}
	
	public function update(SplSubject $subject)
	{
		$event = $subject->getLastEvent();
		switch($event["name"]){
			case "connect":
			case "sentHeaders":
			case "sentBodyPart":
			case "sentBody":
				print("Event[".$event["name"]."]:".$event["data"].PHP_EOL);
				break;
			case "receivedHeaders":
				foreach($event["data"]->getHeader() as $name => $value){
					print("Event[receivedHeaders]:".$name." => ".$value.PHP_EOL);
				}
				break;
			case "receivedEncodedBodyPart":
			case "receivedBodyPart":
				//print("Event[".$event["name"]."]:".$event["data"].PHP_EOL);
				$call = $this->callback;
				$call($event["data"]);
				break;
			case "disconnect":
			case "receivedBody":
			default:
				print("Event:".$event["name"].PHP_EOL);
				break;
		}
	}
}
class Adapter_Custom extends HTTP_Request2_Adapter_Curl
{
    protected function createCurlHandle()
    {
        $ch = parent::createCurlHandle();
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1); //under1B/s
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 85); //85s
        return $ch;
    }
}
function create_consumer_stream(&$consumer, $callback){
	global $http_request;
	$http_adapter = new Adapter_Custom();
	$http_observer = new Observer_Custom($callback);
	$http_request_stream = clone $http_request;
	$http_request_stream->setAdapter($http_adapter);
	$http_request_stream->attach($http_observer);
	$consumer_request_stream = new HTTP_OAuth_Consumer_Request();
	$consumer_request_stream->accept($http_request_stream);
	$consumer_stream = clone $consumer;
	$consumer_stream->accept($consumer_request_stream);
	return $consumer_stream;
}


// Tweets
function statuses_destroy(&$consumer, $id)
{
	$resource_url = "https://api.twitter.com/1.1/statuses/destroy/".$id.".json";
	return twitter_access($consumer, $resource_url, array(), "POST");
}
function statuses_update(&$consumer, $status, $arg = array())
{
	$resource_url = "https://api.twitter.com/1.1/statuses/update.json";
	$arg["status"] = $status;
	return twitter_access($consumer, $resource_url, $arg, "POST");
}

// Search
// Streaming
function user_stream(&$consumer, $arg = array(), $callback)
{
	$resource_url = "https://userstream.twitter.com/1.1/user.json";
	return twitter_stream($consumer, $resource_url, $arg, $callback);
}

// Direct Messages
// Friends & Followers
function friends_ids(&$consumer, $arg = array())
{
	$resource_url = "https://api.twitter.com/1.1/friends/ids.json";
	return twitter_access($consumer, $resource_url, $arg, "GET");
}
function followers_ids(&$consumer, $arg = array())
{
	$resource_url = "https://api.twitter.com/1.1/followers/ids.json";
	return twitter_access($consumer, $resource_url, $arg, "GET");
}
function friendships_create(&$consumer, $arg)
{
	$resource_url = "https://api.twitter.com/1.1/friendships/create.json";
	return twitter_access($consumer, $resource_url, $arg, "POST");
}
function friendships_destroy(&$consumer, $arg)
{
	$resource_url = "https://api.twitter.com/1.1/friendships/destroy.json";
	return twitter_access($consumer, $resource_url, $arg, "POST");
}

// Users
function account_update_profile_image(&$consumer, $image_base64)
{
	$resource_url = "https://api.twitter.com/1.1/account/update_profile_image.json";
	$arg = array("image" => $image_base64);
	return twitter_access($consumer, $resource_url, $arg, "POST");
}
function users_lookup(&$consumer, $arg)
{
	$resource_url = "https://api.twitter.com/1.1/users/lookup.json";
	return twitter_access($consumer, $resource_url, $arg, "GET");
}

// Suggested Users
// Favorites
// Lists
// Saved Searches
// Places & Geo
// Trends
// Spam Reporting
function users_report_spam(&$consumer, $arg)
{
	$resource_url = "https://api.twitter.com/1.1/users/report_spam.json";
	return twitter_access($consumer, $resource_url, $arg, "POST");
}

// OAuth
// Help
function help_configuration(&$consumer)
{
	$resource_url = "https://api.twitter.com/1.1/help/configuration.json";
	return twitter_access($consumer, $resource_url, array(), "GET");
}


// Url Length Load
function get_configuration(&$consumer)
{
	if($result = help_configuration($consumer)){
		//print_r($result);
		if(isset($result->short_url_length)
			&&isset($result->short_url_length_https)){
			$config = EConfig::getInstance();
			$config->short_url_length = $result->short_url_length;
			$config->short_url_length_https = $result->short_url_length_https;
			unset($config);
		}
		return true;
	}
	return false;
}
function get_short_url_length(&$consumer = null)
{
	if($consumer === null){
		$consumer_null = true;
		global $mint_bot_l;
		$consumer = $mint_bot_l;
	} else {
		$consumer_null = false;
	}
	$config = EConfig::getInstance();
	if(!isset($config->short_url_length)||!isset($config->short_url_length_https)){
		unset($config);
		print("No Set Url Length");
		if(get_configuration($consumer)){
			print("->Url Length Loaded.".PHP_EOL);
			$config = EConfig::getInstance();
		} else {
			print("->Url Length Load Failed.".PHP_EOL);
			return false;
		}
	}
	$short_url_length = $config->short_url_length;
	$short_url_length_https = $config->short_url_length_https;
	unset($config);
	if($consumer_null){
		$consumer = null;
	}
	return array($short_url_length, $short_url_length_https);
}

// Editing Tweet
function tweet_message(&$consumer, $message, $reply_id = null, $wait = null)
{
	list($short_url_length, $short_url_length_https)
		= get_short_url_length($consumer);
	
	$ext = Tweet_Extract::create($message);
	if($result = $ext->extractTweet()){
		$length = 0;
		foreach($result as $part){
			if($part[1] === true){
				//url
				if(stripos($part[0], "https") === 0){
					$length += $short_url_length_https;
				} else {
					$length += $short_url_length;
				}
			} else {
				$length += mb_strlen($part[0]);
			}
		}
		print("Length:".$length);
		$over = $length - 140;
		if($over > 0){
			print("->NG. Trim...".PHP_EOL);
			$over++; // "…"の分
			$message = "";
			while(count($result) !== 0){
				$part = array_pop($result);
				if(($part[1] === true)||($over <= 0)){
					$message = $part[0].$message;
				} else {
					$part_length = mb_strlen($part[0]);
					$over -= $part_length;
					if($over <= 0){
						if(($trim = mb_substr($part[0], 0, -$over))
							 !== false){
							$message = $trim."…".$message;
						}
					}
				}
			}
		} else {
			print("->OK.".PHP_EOL);
		}
	}
	
	if($reply_id !== null){
		$arg = array("in_reply_to_status_id" => $reply_id);
	} else {
		$arg = array();
	}
	
	if($wait === null){
		$wait = rand(5, 10);
	}
	print("Tweet Message after ".$wait."sec.".PHP_EOL.$message.PHP_EOL);
	sleep($wait);
	
	return statuses_update($consumer, $message, $arg);
}

// Trim Name
function trim_name($screen_name, $name)
{
	// 固有値
	$namelist = array(
					"kuhma_sohju" => "すみませんP（KuhmaSohju）",
					"GOTS_FRUIT_P" => "フルーツ(笑)P",
					);
	if(isset($namelist[$screen_name])){
		return $namelist[$screen_name];
	}
	
	// 区切りで分割
	$name_part      = preg_split("/[\(\)：；（）【】@＠\/／♪]/u",
								 $name);
	$ret = "";
	foreach($name_part as $item){
		if(preg_match("/(\w|[ぁ-ヶ]|[亜-黑])/u", $item) == 1){
			if($ret == ""){
				$ret = $item;
				if(preg_match("/[PＰ]$/u", $item) !== 1){
					$ret .= "さん";
				}
			} else if(preg_match("/[PＰ]$/u", $item) == 1){
				if(preg_match("/ボカロ[PＰ]/u", $item) !== 1){
					$ret = $item;
				}
			}
		}
	}
	if($ret == ""){
		return $name;
	}
	return $ret;
}

?>
