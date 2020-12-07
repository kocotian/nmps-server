<?php

require_once "require/nmps.php";

foreach (userAccount::getOnline() as $onlineUser) {
	if ($onlineUser['userId'] != $userinfo['id'])
		db::query("INSERT INTO events (userId, senderId, eventType, eventData) VALUES (:userId, :senderId, :eventType, :eventData)",
			[':userId' => $onlineUser['userId'], ':senderId' => $userinfo['id'],
			':eventType' => 2, ':eventData' => "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;37m" . $userinfo['username'] . "\033[1;37m: \033[0;97m" . implode(' ', $argv)]);
}

echo "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;37m" . $userinfo['username'] . "\033[1;37m: \033[0;97m" . implode(' ', $argv);
