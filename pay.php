<?php

	require_once "require/nmps.php";

	$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
	if ($user) {
		echo "\033[1;33musername:        \033[0;33m" . $user['username'] . "\n";
		echo "\033[1;33mlast online:     \033[0;33m" . $user['lastOnline'] . "\n";
		echo "\033[1;33mmoney:           \033[0;33m" . $user['money'] . "\n";
		echo "\033[1;33mlevel:           \033[0;33m" . $user['level'] . "\n";
		echo "\033[1;33mxp:              \033[0;33m" . $user['xp'] . "\n";
	} else
		echo "\033[1;31mUser doesn't exists";
