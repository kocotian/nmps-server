<?php

	$unauthorized = 1;
	require_once "require/nmps.php";

	$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
	if ($user)
		if (password_verify($argv[1], $user['password'])) {
			if (!count(db::query("SELECT * FROM authTokens WHERE userId=:userId", [':userId' => $user['id']]))) {
				$cstrong = true;
				$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
				db::query("INSERT INTO authTokens (userId, token, addressBefore, addressAfter) VALUES (:userId, :token, :addressBefore, :addressAfter)", [':userId' => $user['id'], ':token' => sha1($token), ':addressBefore' => $user['lastAddress'], ':addressAfter' => $_SERVER['REMOTE_ADDR']]);
				db::query("UPDATE users SET lastAddress=:ip WHERE id=:userId", [':ip' => $_SERVER['REMOTE_ADDR'], ':userId' => $user['id']]);
				echo "Authorized\n" . $token;
			} else
				echo "You can log in on one account in the same time only";
		} else
			echo "Wrong password";
	else
		echo "Unexisting account";
