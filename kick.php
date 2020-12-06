<?php

require_once "require/nmps.php";

if ($userinfo['op'] < 3)
	die("\033[1;31mYour op level is too low");

if (!($target = userAccount::usernameToId($argv[0])))
	die("\033[1;31mUser doesn't exists");

if (!userAccount::isUserOnline($target))
	die("\033[1;31mUser is offline");

unset($argv[0]);
db::query("DELETE FROM authTokens WHERE userId=:userId",
	[':userId' => $target]);

db::query("INSERT INTO events (userId, senderId, eventType, eventData) VALUES (:userId, :senderId, :eventType, :eventData)",
	[':userId' => $target, ':senderId' => $userinfo['id'],
	':eventType' => 126, ':eventData' => "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;37mYou were kicked by \033[1;97m" . $userinfo['username'] . " \033[0;37mfor \033[1;31m" . (count($argv) ? implode(' ', $argv) : "no reason")]);

echo "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;32mKicked \033[0;37m" . userAccount::idToUsername($target) . "\033[1;37m.";
