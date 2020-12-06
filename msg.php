<?php

require_once "require/nmps.php";

if (!($target = userAccount::usernameToId($argv[0])))
	die("\033[1;31mUser doesn't exists");

if (!userAccount::isUserOnline($target))
	die("\033[1;31mUser is offline");

unset($argv[0]);
db::query("INSERT INTO events (userId, senderId, eventType, eventData) VALUES (:userId, :senderId, :eventType, :eventData)",
	[':userId' => $target, ':senderId' => $userinfo['id'],
	':eventType' => 0, ':eventData' => "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;37m" . $userinfo['username'] . " \033[1;37m-> \033[0;31mYou\033[1;37m: \033[0;97m" . implode(' ', $argv)]);

echo "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;31mYou \033[1;37m-> \033[0;37m" . userAccount::idToUsername($target) . "\033[1;37m: \033[0;97m" . implode(' ', $argv);
