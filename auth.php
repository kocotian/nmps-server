<?php

	require_once "require/nmps.php";

	$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
	if ($user)
		if (password_verify($argv[1], $user['password'])) {
			$cstrong = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			db::query("INSERT INTO authTokens (userId, token) VALUES (:userId, :token)", [':userId' => $user['id'], ':token' => sha1($token)]);
			echo "Authorized\n" . $token;
		}
		else
			echo "Wrong password";
	else
		echo "Unexisting account";
