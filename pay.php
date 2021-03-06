<?php

require_once "require/nmps.php";

$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
if (count($user)) {
	if (is_numeric($argv[1])) {
		if ($argv[1] > 0) {
			if ($userinfo['money'] >= $argv[1]) {
				db::query("UPDATE users SET money = money - :amount WHERE id=:id", [':amount' => $argv[1], ':id' => $userinfo['id']]);
				db::query("UPDATE users SET money = money + :amount WHERE id=:id", [':amount' => $argv[1], ':id' => $user['id']]);
				nmps::sendEvent($user['id'], $userinfo['id'], 0,
					"\033[1;97m" . $userinfo['username'] . " \033[1;37msend you \033[1;32m$" . $argv[1]);
				nmps::printEvent("\033[1;37mSent \033[1;32m$" . $argv[1] . "\033[0;37m to \033[1;97m" . $user['username']);
			} else
				echo "\033[1;31mYou don't have enough money";
		} else
			echo "\033[1;31mYou can pay money that's greater than 0 only 😳";
	} else
		echo "\033[1;31mArgument is not a number";
} else
	echo "\033[1;31mUser doesn't exists";
