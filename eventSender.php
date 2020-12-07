<?php

require_once "require/nmps.php";

$iterations = 0;
while (!count($events = db::query("SELECT * FROM events WHERE userId=:userId",
	[':userId' => $userinfo['id']])) && ++$iterations < 45) {
	usleep(1000000);
}

foreach ($events as $event) {
	echo ($event['eventType'] == 126 ? "\004\001" : "") . $event['eventData'] . "\n";
	db::query("DELETE FROM events WHERE id=:id", [':id' => $event['id']]);
}

if (!count($events))
	echo "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[1;97mServer \033[0;37mpinged you";
