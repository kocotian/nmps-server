<?php
	require_once "require/nmps.php";

	db::query("DELETE FROM authTokens WHERE token=:token", [':token' => sha1($authToken)]);
	echo "\004\001";
