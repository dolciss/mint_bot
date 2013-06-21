<?php
// animesp_check.php
// ニコニコアニメスペシャルチェック

require_once("Twitter.php");
require_once("Nicovideo.php");
require_once("alert_live.php");

print("anime-sp check...");
$url = "http://ch.nicovideo.jp/anime-sp";
$data = file_get_contents($url);

$add = "";
$pattern = "/vid\" value=\"(\d+)\">.*?title\" value=\"(.*?)\">.*?open_time\" value=\"(\d+)\">/su";
if(preg_match_all($pattern, $data, $match, PREG_SET_ORDER) > 0){
	foreach($match as $item){
		$liveid = "lv".$item[1];
		$title = htmlspecialchars_decode($item[2], ENT_QUOTES);
		$opentime = $item[3];
		if($opentime < (time() + 3600)){
			// past...
			continue;
		}
		if(in_array($liveid, $livelist)){
			// already added
			continue;
		}
		preg_match("/「(.*)」/u", $title, $match);
		if(isset($match[1])){
			$short = $match[1];
		} else {
			$short = $title;
		}
		$add .= "\t\t\t\"".$liveid."\", ";
		$add .= "// ".date("m/d ", $opentime).$short.PHP_EOL;
		$message = make_message("ややっ、 ".$url." に",
								$title,
								date("(m/d H:i開場)", $opentime),
								"が追加されたみたい？",
								"http://nico.ms/".$liveid,
								" @L_tan");
		statuses_update($mint_bot_m, $message);
	}
}
if($add === ""){
	print("new live not found...".PHP_EOL);
	exit();
}

$fp = fopen("alert_live.php", "r+");
if($fp){
    flock($fp, LOCK_EX);
    while($line = fgets($fp, 1024)){
        // skip
        if(strpos($line,"// animesp_check") !== false){
            break;
        }
    }
    if(!feof($fp)){
        $pos = ftell($fp);
        $remain = fread($fp, 1024*1024);
        fseek($fp, $pos);
        fwrite($fp, $add);
        fwrite($fp, $remain);
    } else {
        print("// animesp_check not found...".PHP_EOL);
    }
    fclose($fp);
}
print("alert_live.php written.".PHP_EOL);

?>
