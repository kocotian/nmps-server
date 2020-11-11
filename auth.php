<?php

	require_once "require/argv.php";
	require_once "require/dbconnect.php";
	require_once "require/dbutils.php";

	$user = db::query("SELECT * FROM users WHERE username=:username", ['username' => $argv[0]])[0];
	if ($user)
		if (password_verify($argv[1], $user['password']))
			echo "Authorized\naa13d2b75605c3472101c195c5cd1cb601daa320";
		else
			echo "Wrong password";
	else
		echo "Unexisting account";
