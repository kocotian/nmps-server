<?php
require_once "require/nmps.php";

$onlineUsers = userAccount::getOnline();
echo "\033[1;97mNow online:";
foreach ($onlineUsers as $onlineUser) {
	$onlineUserUsername = userAccount::idToUsername($onlineUser['userId']);
	echo "\n\033[0;97m" . $onlineUserUsername . ($userinfo['op'] > 2 ? (str_repeat(" ", 16 - strlen($onlineUserUsername)) . "\033[0;33m" . $onlineUser['addressAfter']) : '');
}
