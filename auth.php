<?php

	$unauthorized = 1;
	require_once "require/nmps.php";
	if (strlen($authToken) != 6)
		die("\033[1;31mYou are already logged in");

	$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
	if ($user)
		if (password_verify($argv[1], $user['password'])) {
			if (count(db::query("SELECT * FROM authTokens WHERE userId=:userId", [':userId' => $user['id']]))) {
				db::query("DELETE FROM authTokens WHERE userId=:userId", [':userId' => $user['id']]);
			} else
			$cstrong = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			db::query("INSERT INTO authTokens (userId, token, addressBefore, addressAfter) VALUES (:userId, :token, :addressBefore, :addressAfter)", [':userId' => $user['id'], ':token' => sha1($token), ':addressBefore' => $user['lastAddress'], ':addressAfter' => $_SERVER['REMOTE_ADDR']]);
			db::query("UPDATE users SET lastAddress=:ip WHERE id=:userId", [':ip' => $_SERVER['REMOTE_ADDR'], ':userId' => $user['id']]);
			foreach (userAccount::getOnline() as $onlineUser) {
				if ($onlineUser['userId'] != $user['id'])
					nmps::sendEvent($onlineUser['userId'], $user['id'], 2,
						"\033[0;37m" . $user['username'] . " \033[0;32mjoined.");
			}
			echo "Authorized\n" . $token;
		} else
			die("Wrong password");
	else
		die("Unexisting account");
