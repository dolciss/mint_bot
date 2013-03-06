<?php
// configuration.php
// help/configurationで設定値(短縮URLの文字数)を読み込む

require_once("EConfig2.php");
require_once("Twitter.php");

$config = EConfig2::getInstance();
$now = time();
if(isset($config->last_get_configuration)){
	if($now < ($config->last_get_configuration + 24*60*60)){
		print("Last Get Configuration Interval Short.".PHP_EOL);
		exit();
	}
}
if(get_configuration($mint_bot_l)){
	print("Configuration Update.".PHP_EOL);
	$config->last_get_configuration = $now;
}
unset($config);

?>
