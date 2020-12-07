<?php

	require_once "require/nmps.php";

	if (!strlen($argv[0])) $argv[0] = $userinfo['username'];
	$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
	if ($user) {
		echo "\033[1;97musername:        \033[0;97m" . $user['username'] . "\n";
		echo "\033[1;37mlast online:     \033[0;33m" . $user['lastOnline'] . "\n";
		echo "\033[1;37mmoney:           \033[0;33m" . $user['money'] . "\n";
		echo "\033[1;37mhealth:          \033[0;33m" . $user['health'] . "\n";
		echo "\033[1;37menergy:          \033[0;33m" . $user['energy'] . "\n";
		echo "\033[1;37msaturation:      \033[0;33m" . $user['saturation'] . "\n";
		echo "\033[1;37msanity:          \033[0;33m" . $user['sanity'] . "\n";
		echo "\033[1;37mxp:              \033[0;33m" . $user['xp'] . "\n";
	} else
		echo "\033[1;31mUser doesn't exists";
