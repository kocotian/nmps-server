<?php
	require_once "require/nmps.php";

	db::query("DELETE FROM authTokens WHERE userId=:userId", [':userId' => $userinfo['id']]);
	echo "\004\001";
