<?php
	require_once "require/nmps.php";

	foreach (userAccount::getOnline() as $onlineUser) {
		if ($onlineUser['userId'] != $userinfo['id'])
			nmps::sendEvent($onlineUser['userId'], $userinfo['id'], 3,
				"\033[0;37m" . $userinfo['username'] . " \033[0;31munjoined.");
	}
	db::query("DELETE FROM authTokens WHERE userId=:userId", [':userId' => $userinfo['id']]);
	echo "\004\001";
